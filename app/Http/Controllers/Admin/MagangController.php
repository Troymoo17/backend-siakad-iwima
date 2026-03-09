<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\LaporanMagang;
use App\Models\Mahasiswa;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagangController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = PengajuanMagang::with('mahasiswa');
        if ($nim = $request->nim)   $query->where('nim','like',"%$nim%");
        if ($request->status_magang) $query->where('status_magang',$request->status_magang);
        $magangList = $query->orderBy('tgl_pengajuan','desc')->paginate(20)->withQueryString();
        return view('admin.magang.index', compact('magangList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $m = PengajuanMagang::findOrFail($id);
        $v = $request->validate([
            'status_magang'  => 'required|in:Menunggu,Diterima,Ditolak',
            'komentar_prodi' => 'nullable|string',
        ]);
        $v['tgl_proses'] = now()->toDateString();
        $m->update($v);
        $this->notif->notifyMagangDiproses($m->nim, $v['status_magang'], $m->nama_tempat_magang);
        return redirect()->back()->with('success',"Status magang diperbarui ke {$v['status_magang']}.");
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }
}
