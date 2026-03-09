<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class KrsAdminController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = Krs::with('mahasiswa','mataKuliah');
        if ($nim = $request->nim) $query->where('nim','like',"%$nim%");
        if ($request->semester)   $query->where('semester',$request->semester);
        if ($request->tahun_akademik) $query->where('tahun_akademik',$request->tahun_akademik);
        if ($request->status)     $query->where('status',$request->status);
        if ($request->disetujui !== null && $request->disetujui !== '') $query->where('disetujui_dosen',$request->disetujui);
        $krsList = $query->orderBy('nim')->orderBy('semester')->paginate(25)->withQueryString();
        return view('admin.krs.index', compact('krsList'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nim'            => 'required|exists:mahasiswa,nim',
            'semester'       => 'required|integer|min:1|max:14',
            'tahun_akademik' => 'required|string',
            'kode_mk'        => 'required|array|min:1',
            'kode_mk.*'      => 'exists:mata_kuliah,kode_mk',
        ]);
        $inserted = 0;
        foreach ($v['kode_mk'] as $mk) {
            Krs::updateOrCreate(
                ['nim'=>$v['nim'],'semester'=>$v['semester'],'tahun_akademik'=>$v['tahun_akademik'],'kode_mk'=>$mk],
                ['status'=>'Aktif','disetujui_dosen'=>0]
            );
            $inserted++;
        }
        return redirect()->route('admin.krs.index')->with('success',"$inserted mata kuliah berhasil ditambahkan ke KRS.");
    }

    public function approve(Request $request, $id)
    {
        $krs = Krs::with('mahasiswa')->findOrFail($id);
        $krs->update(['disetujui_dosen' => 1]);
        $this->notif->notifyKrsApproved($krs->nim, $krs->semester, $krs->tahun_akademik);
        return redirect()->back()->with('success','KRS disetujui & notifikasi dikirim.');
    }

    public function approveAll(Request $request)
    {
        $request->validate(['nim'=>'required','semester'=>'required','tahun_akademik'=>'required']);
        $updated = Krs::where('nim',$request->nim)->where('semester',$request->semester)
                      ->where('tahun_akademik',$request->tahun_akademik)
                      ->update(['disetujui_dosen'=>1]);
        if ($updated) $this->notif->notifyKrsApproved($request->nim,(int)$request->semester,$request->tahun_akademik);
        return redirect()->back()->with('success',"Semua KRS disetujui ($updated MK).");
    }

    public function destroy($id)
    {
        Krs::findOrFail($id)->delete();
        return redirect()->back()->with('success','KRS dihapus.');
    }

    // AJAX: cari mahasiswa by NIM
    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang','prodi']);
        return response()->json(['success'=> (bool)$mhs, 'data'=>$mhs]);
    }
}
