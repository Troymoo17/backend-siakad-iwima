<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPengaduan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengaduanController extends Controller
{
    protected NotificationService $notifService;

    public function __construct(NotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    public function index()
    {
        $pengaduan = SuratPengaduan::with('mahasiswa', 'dosen')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function show($id)
    {
        $surat = SuratPengaduan::with('mahasiswa', 'dosen')->findOrFail($id);
        if ($surat->status === 'Terkirim') {
            $surat->update(['status' => 'Dibaca']);
        }
        return view('admin.pengaduan.show', compact('surat'));
    }

    public function balas(Request $request, $id)
    {
        $surat = SuratPengaduan::findOrFail($id);
        $request->validate(['balasan' => 'required|string']);

        $surat->update([
            'balasan' => $request->balasan,
            'balasan_oleh' => Session::get('admin_nama'),
            'balasan_at' => now(),
            'status' => 'Selesai',
        ]);

        $this->notifService->notifyPengaduanDibalas($surat->nim, $surat->perihal);

        return redirect()->back()->with('success', 'Balasan terkirim dan notifikasi dikirim ke mahasiswa.');
    }
}
