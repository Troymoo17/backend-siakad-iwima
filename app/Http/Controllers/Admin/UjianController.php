<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjian;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UjianController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = JadwalUjian::with('dosen');
        if ($s = $request->search) $query->where(fn($q) => $q->where('mata_kuliah','like',"%$s%")->orWhere('kelas','like',"%$s%")->orWhere('kode_mk','like',"%$s%"));
        if ($request->jenis_ujian)    $query->where('jenis_ujian',$request->jenis_ujian);
        if ($request->kelas)          $query->where('kelas',$request->kelas);
        if ($request->tahun_akademik) $query->where('tahun_akademik',$request->tahun_akademik);
        $ujians    = $query->orderBy('tanggal')->orderBy('mulai')->paginate(25)->withQueryString();
        $mkList    = MataKuliah::orderBy('semester')->get();
        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        return view('admin.ujian.index', compact('ujians','mkList','dosenList'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'kode_mk'        => 'required|exists:mata_kuliah,kode_mk',
            'kelas'          => 'required|string|max:20',
            'dosen_id'       => 'nullable|exists:dosen,id',
            'jenis_ujian'    => 'required|in:UTS,UAS',
            'tanggal'        => 'required|date',
            'hari'           => 'required|string',
            'mulai'          => 'required',
            'selesai'        => 'required',
            'ruangan'        => 'required|string|max:50',
            'semester'       => 'required|integer',
            'tahun_akademik' => 'required|string',
            'soal'           => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $mk = MataKuliah::find($v['kode_mk']);
        $v['mata_kuliah'] = $mk->nama_mk;

        if ($request->hasFile('soal')) {
            $file = $request->file('soal');
            $filename = time().'_soal_'.$v['jenis_ujian'].'_'.$v['kode_mk'].'.'.$file->extension();
            $file->storeAs('ujian', $filename, 'public');
            $v['soal'] = $filename;
        }

        JadwalUjian::create($v);

        // Notifikasi ke kelas
        $this->notif->sendToKelas(
            $v['kelas'],
            "Jadwal {$v['jenis_ujian']} - {$mk->nama_mk}",
            "Ujian {$v['jenis_ujian']} mata kuliah {$mk->nama_mk} akan dilaksanakan pada ".date('d M Y', strtotime($v['tanggal']))." pukul {$v['mulai']} di ruang {$v['ruangan']}.",
            'akademik', '/jadwal-ujian'
        );

        return redirect()->route('admin.ujian.index')->with('success','Jadwal ujian ditambahkan & notifikasi dikirim ke kelas '.$v['kelas'].'.');
    }

    public function edit($id)
    {
        $ujian     = JadwalUjian::findOrFail($id);
        $mkList    = MataKuliah::orderBy('semester')->get();
        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        return view('admin.ujian.edit', compact('ujian','mkList','dosenList'));
    }

    public function update(Request $request, $id)
    {
        $ujian = JadwalUjian::findOrFail($id);
        $v = $request->validate([
            'kode_mk'        => 'required|exists:mata_kuliah,kode_mk',
            'kelas'          => 'required|string|max:20',
            'dosen_id'       => 'nullable|exists:dosen,id',
            'jenis_ujian'    => 'required|in:UTS,UAS',
            'tanggal'        => 'required|date',
            'hari'           => 'required|string',
            'mulai'          => 'required',
            'selesai'        => 'required',
            'ruangan'        => 'required|string|max:50',
            'semester'       => 'required|integer',
            'tahun_akademik' => 'required|string',
            'soal'           => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        $mk = MataKuliah::find($v['kode_mk']);
        $v['mata_kuliah'] = $mk->nama_mk;
        if ($request->hasFile('soal')) {
            if ($ujian->soal) Storage::disk('public')->delete('ujian/'.$ujian->soal);
            $file = $request->file('soal');
            $filename = time().'_soal_'.$v['jenis_ujian'].'_'.$v['kode_mk'].'.'.$file->extension();
            $file->storeAs('ujian', $filename, 'public');
            $v['soal'] = $filename;
        }
        $ujian->update($v);
        return redirect()->route('admin.ujian.index')->with('success','Jadwal ujian diperbarui.');
    }

    public function destroy($id)
    {
        $u = JadwalUjian::findOrFail($id);
        if ($u->soal) Storage::disk('public')->delete('ujian/'.$u->soal);
        $u->delete();
        return redirect()->route('admin.ujian.index')->with('success','Jadwal ujian dihapus.');
    }
}
