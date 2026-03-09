<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkripsiPengajuan;
use App\Models\BimbinganSkripsi;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SkripsiController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = SkripsiPengajuan::with('mahasiswa','pembimbing1','pembimbing2');
        if ($nim = $request->nim)   $query->where('nim','like',"%$nim%");
        if ($request->status)       $query->where('status',$request->status);
        $pengajuans = $query->orderBy('tgl_pengajuan','desc')->paginate(20)->withQueryString();

        $bimbinganQuery = BimbinganSkripsi::with('mahasiswa','dosen');
        if ($nim = $request->nim_bimb) $bimbinganQuery->where('nim','like',"%$nim%");
        $bimbingans = $bimbinganQuery->orderBy('tanggal','desc')->paginate(20, ['*'], 'bimb_page')->withQueryString();

        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        return view('admin.skripsi.index', compact('pengajuans','bimbingans','dosenList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $p = SkripsiPengajuan::with('mahasiswa')->findOrFail($id);
        $v = $request->validate([
            'status'         => 'required|in:Diajukan,Disetujui,Ditolak',
            'pembimbing1_id' => 'nullable|exists:dosen,id',
            'pembimbing2_id' => 'nullable|exists:dosen,id',
            'komentar_prodi' => 'nullable|string',
        ]);
        $v['tgl_proses'] = now()->toDateString();
        $p->update($v);

        $pesanStatus = $v['status'] === 'Disetujui' ? 'disetujui' : 'ditolak';
        $this->notif->sendToMahasiswa($p->nim, "Pengajuan Skripsi $pesanStatus",
            "Pengajuan skripsi Anda \"{$p->judul}\" telah {$pesanStatus}." . ($v['komentar_prodi'] ? ' Catatan: '.$v['komentar_prodi'] : ''),
            'skripsi', '/skripsi');

        return redirect()->back()->with('success',"Status skripsi diperbarui ke {$v['status']}.");
    }

    public function updateBimbingan(Request $request, $id)
    {
        $b = BimbinganSkripsi::findOrFail($id);
        $v = $request->validate([
            'catatan_dosen' => 'nullable|string',
            'status'        => 'required|in:Menunggu,Diterima,Revisi',
        ]);
        $b->update($v);
        $this->notif->notifyBimbinganUpdate($b->nim, $b->bab, $v['status']);
        return redirect()->back()->with('success','Status bimbingan diperbarui & notifikasi dikirim.');
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }
}
