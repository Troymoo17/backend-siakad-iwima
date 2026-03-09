<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Khs;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    protected NotificationService $notifService;

    public function __construct(NotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    public function index(Request $request)
    {
        $query = Nilai::with('mahasiswa', 'mataKuliah', 'dosen');

        if ($request->nim) $query->where('nim', $request->nim);
        if ($request->kode_mk) $query->where('kode_mk', $request->kode_mk);
        if ($request->semester) $query->where('semester', $request->semester);

        $nilaiList = $query->orderBy('nim')->paginate(25)->withQueryString();
        $mataKuliahList = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();

        return view('admin.nilai.index', compact('nilaiList', 'mataKuliahList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'semester' => 'required|integer',
            'tahun_akademik' => 'required|string',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'dosen_id' => 'nullable|exists:dosen,id',
        ]);

        // Hitung nilai akhir (30% tugas + 30% UTS + 40% UAS)
        $nilaiTugas = $validated['nilai_tugas'] ?? 0;
        $nilaiUts = $validated['nilai_uts'] ?? 0;
        $nilaiUas = $validated['nilai_uas'] ?? 0;
        $nilaiAkhir = ($nilaiTugas * 0.3) + ($nilaiUts * 0.3) + ($nilaiUas * 0.4);

        $gradeInfo = Nilai::hitungGrade($nilaiAkhir);
        $mk = MataKuliah::find($validated['kode_mk']);

        $validated['nilai_akhir'] = round($nilaiAkhir, 2);
        $validated['grade'] = $gradeInfo['grade'];
        $validated['bobot'] = $gradeInfo['bobot'];
        $validated['sks'] = $mk->sks;
        $validated['bobot_sks'] = $gradeInfo['bobot'] * $mk->sks;

        $nilai = Nilai::updateOrCreate(
            ['nim' => $validated['nim'], 'kode_mk' => $validated['kode_mk'], 'semester' => $validated['semester']],
            $validated
        );

        // Update KHS
        $this->updateKhs($validated['nim'], $validated['semester'], $validated['tahun_akademik']);

        // Kirim notifikasi ke mahasiswa
        $this->notifService->notifyNilaiInput($validated['nim'], $mk->nama_mk, $validated['semester']);

        return redirect()->back()->with('success', 'Nilai berhasil disimpan dan notifikasi dikirim.');
    }

    private function updateKhs(string $nim, int $semester, string $tahunAkademik): void
    {
        $nilaiSemester = Nilai::where('nim', $nim)->where('semester', $semester)->get();
        $totalBobot = $nilaiSemester->sum('bobot_sks');
        $totalSks = $nilaiSemester->sum('sks');
        $ips = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        // Hitung kumulatif
        $nilaiAll = Nilai::where('nim', $nim)->where('semester', '<=', $semester)->get();
        $totalBobotAll = $nilaiAll->sum('bobot_sks');
        $totalSksAll = $nilaiAll->sum('sks');
        $ipk = $totalSksAll > 0 ? round($totalBobotAll / $totalSksAll, 2) : 0;

        Khs::updateOrCreate(
            ['nim' => $nim, 'semester' => $semester],
            [
                'tahun_akademik' => $tahunAkademik,
                'total_sks' => $totalSks,
                'total_sks_kumulatif' => $totalSksAll,
                'ips' => $ips,
                'ipk' => $ipk,
            ]
        );
    }
}
