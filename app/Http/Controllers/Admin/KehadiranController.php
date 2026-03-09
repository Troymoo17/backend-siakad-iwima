<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with('mahasiswa','mataKuliah');
        if ($nim = $request->nim) $query->where('nim','like',"%$nim%");
        if ($request->kode_matkul) $query->where('kode_matkul',$request->kode_matkul);
        if ($request->status)      $query->where('status',$request->status);
        $kehadirans = $query->orderBy('tanggal','desc')->paginate(30)->withQueryString();

        // Rekap per NIM jika dicari
        $rekap = null;
        if ($nim = $request->nim) {
            $rekap = Kehadiran::where('nim',$request->nim)
                ->select('kode_matkul',
                    DB::raw("SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) as hadir"),
                    DB::raw("SUM(CASE WHEN status='Sakit' THEN 1 ELSE 0 END) as sakit"),
                    DB::raw("SUM(CASE WHEN status='Izin' THEN 1 ELSE 0 END) as izin"),
                    DB::raw("SUM(CASE WHEN status='Tidak Hadir' THEN 1 ELSE 0 END) as alpha"),
                    DB::raw("COUNT(*) as total"))
                ->groupBy('kode_matkul')->with('mataKuliah')->get();
        }
        $mkList = MataKuliah::orderBy('semester')->get();
        return view('admin.kehadiran.index', compact('kehadirans','rekap','mkList'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nim'         => 'required|exists:mahasiswa,nim',
            'kode_matkul' => 'required|exists:mata_kuliah,kode_mk',
            'pertemuan'   => 'required|integer|min:1',
            'status'      => 'required|in:Hadir,Tidak Hadir,Sakit,Izin',
            'tanggal'     => 'required|date',
            'keterangan'  => 'nullable|string|max:255',
        ]);
        Kehadiran::updateOrCreate(
            ['nim'=>$v['nim'],'kode_matkul'=>$v['kode_matkul'],'pertemuan'=>$v['pertemuan']],
            $v
        );
        return redirect()->back()->with('success','Kehadiran disimpan.');
    }

    // Bulk input kehadiran satu pertemuan untuk semua mahasiswa dalam kelas
    public function bulkStore(Request $request)
    {
        $request->validate([
            'kode_matkul'  => 'required|exists:mata_kuliah,kode_mk',
            'kelas'        => 'required|string',
            'pertemuan'    => 'required|integer|min:1',
            'tanggal'      => 'required|date',
            'kehadiran'    => 'required|array',
            'kehadiran.*'  => 'required|in:Hadir,Tidak Hadir,Sakit,Izin',
        ]);
        $count = 0;
        foreach ($request->kehadiran as $nim => $status) {
            Kehadiran::updateOrCreate(
                ['nim'=>$nim,'kode_matkul'=>$request->kode_matkul,'pertemuan'=>$request->pertemuan],
                ['status'=>$status,'tanggal'=>$request->tanggal]
            );
            $count++;
        }
        return redirect()->back()->with('success',"Kehadiran $count mahasiswa berhasil disimpan.");
    }

    public function bulkForm(Request $request)
    {
        $request->validate(['kode_matkul'=>'required','kelas'=>'required']);
        $mahasiswas = Mahasiswa::where('kelas',$request->kelas)->where('status_aktif','Aktif')->orderBy('nama')->get();
        $mk = MataKuliah::find($request->kode_matkul);
        $existingPertemuan = Kehadiran::where('kode_matkul',$request->kode_matkul)->max('pertemuan') ?? 0;
        return view('admin.kehadiran.bulk', compact('mahasiswas','mk','existingPertemuan','request'));
    }

    public function destroy($id)
    {
        Kehadiran::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data kehadiran dihapus.');
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }
}
