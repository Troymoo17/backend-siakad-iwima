<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Tagihan;
use App\Models\SuratPengaduan;
use App\Models\Notifikasi;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mahasiswa' => Mahasiswa::where('status_aktif', 'Aktif')->count(),
            'total_dosen' => Dosen::where('is_active', 1)->count(),
            'total_pengaduan_belum' => SuratPengaduan::whereIn('status', ['Terkirim', 'Dibaca'])->count(),
            'total_tagihan_belum' => Tagihan::where('status_bayar', 'Belum')->sum('nominal_tagihan'),
            'krs_menunggu' => Krs::where('disetujui_dosen', 0)->where('status', 'Aktif')->count(),
        ];

        $mahasiswaPerKelas = Mahasiswa::where('status_aktif', 'Aktif')
            ->select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        $pengumumanTerbaru = Pengumuman::orderBy('created_at', 'desc')->take(5)->get();
        $pengaduanTerbaru = SuratPengaduan::with('mahasiswa')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'mahasiswaPerKelas', 'pengumumanTerbaru', 'pengaduanTerbaru'));
    }
}
