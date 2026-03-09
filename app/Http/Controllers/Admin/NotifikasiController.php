<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\NotifikasiMahasiswa;
use App\Models\Mahasiswa;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NotifikasiController extends Controller
{
    protected NotificationService $notifService;

    public function __construct(NotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    public function index()
    {
        $notifikasi = Notifikasi::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    public function create()
    {
        $kelasList = Mahasiswa::distinct()->orderBy('kelas')->pluck('kelas');
        return view('admin.notifikasi.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'tipe' => 'required|in:info,warning,success,error,pengumuman,akademik,keuangan,krs,nilai,skripsi,umum',
            'target' => 'required|in:all,prodi,kelas,personal',
            'target_value' => 'nullable|string',
            'link' => 'nullable|string',
            'nim_personal' => 'nullable|string|exists:mahasiswa,nim',
        ]);

        $adminId = Session::get('admin_id');

        switch ($validated['target']) {
            case 'all':
                $this->notifService->sendToAll($validated['judul'], $validated['pesan'], $validated['tipe'], $validated['link'] ?? null, $adminId);
                $msg = 'Notifikasi berhasil dikirim ke semua mahasiswa.';
                break;
            case 'prodi':
                $this->notifService->sendToProdi($validated['target_value'], $validated['judul'], $validated['pesan'], $validated['tipe'], $validated['link'] ?? null, $adminId);
                $msg = 'Notifikasi berhasil dikirim ke prodi ' . $validated['target_value'];
                break;
            case 'kelas':
                $this->notifService->sendToKelas($validated['target_value'], $validated['judul'], $validated['pesan'], $validated['tipe'], $validated['link'] ?? null, $adminId);
                $msg = 'Notifikasi berhasil dikirim ke kelas ' . $validated['target_value'];
                break;
            case 'personal':
                $this->notifService->sendToMahasiswa($request->nim_personal, $validated['judul'], $validated['pesan'], $validated['tipe'], $validated['link'] ?? null);
                $msg = 'Notifikasi berhasil dikirim ke mahasiswa ' . $request->nim_personal;
                break;
            default:
                $msg = 'Notifikasi terkirim.';
        }

        return redirect()->route('admin.notifikasi.index')->with('success', $msg);
    }

    public function destroy($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        NotifikasiMahasiswa::where('notifikasi_id', $id)->delete();
        $notifikasi->delete();
        return redirect()->route('admin.notifikasi.index')->with('success', 'Notifikasi dihapus.');
    }
}
