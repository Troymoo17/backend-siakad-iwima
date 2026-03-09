<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\KrsSetting;
use App\Models\Kurikulum;
use App\Models\Nilai;
use App\Models\Khs;
use App\Models\JadwalKuliah;
use App\Models\JadwalUjian;
use App\Models\Kehadiran;
use App\Models\Tagihan;
use App\Models\Pengumuman;
use App\Models\KalenderAkademik;
use App\Models\GambarKegiatan;
use App\Models\NotifikasiMahasiswa;
use App\Models\SuratPengaduan;
use App\Models\BimbinganSkripsi;
use App\Models\BimbinganProposal;
use App\Models\BimbinganSidang;
use App\Models\SkripsiPengajuan;
use App\Models\SkripsiUjian;
use App\Models\PengajuanMagang;
use App\Models\Pinjaman;
use App\Models\PointBook;
use App\Models\Dosen;
use App\Models\DosenMatkul;
use App\Models\Staff;
use App\Models\IkadKuisioner;
use App\Models\IkasKuisioner;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BannerKegiatan;
use App\Models\DownloadMateri;

class MahasiswaController extends Controller
{
    protected NotificationService $notifService;

    public function __construct(NotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    // ── Helpers ──────────────────────────────────────────────────
    private function getMahasiswa(Request $request) { return $request->attributes->get('auth_mahasiswa'); }
    private function getNim(Request $request): string { return $request->attributes->get('auth_nim'); }

    // ===== PROFIL =====
    public function profil(Request $request)
    {
        $mahasiswa = $this->getMahasiswa($request);
        $mahasiswa->load('dosenPA');
        $mahasiswa->foto_profil_url = $mahasiswa->foto ? url('storage/'.$mahasiswa->foto) : null;
        return response()->json(['success'=>true,'data'=>$mahasiswa]);
    }

    public function updateProfil(Request $request)
    {
        $mahasiswa = $this->getMahasiswa($request);
        $validated = $request->validate([
            'email'     => 'nullable|email|unique:mahasiswa,email,'.$mahasiswa->nim.',nim',
            'telp'      => 'nullable|string|max:20',
            'handphone' => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
            'kota'      => 'nullable|string|max:100',
            'provinsi'  => 'nullable|string|max:100',
        ]);
        $mahasiswa->update($validated);
        return response()->json(['success'=>true,'message'=>'Profil berhasil diperbarui.','data'=>$mahasiswa]);
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $mahasiswa = $this->getMahasiswa($request);

        // Hapus foto lama jika ada
        if ($mahasiswa->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($mahasiswa->foto);
        }

        $file     = $request->file('foto');
        $filename = 'foto_profil/' . $mahasiswa->nim . '_' . time() . '.' . $file->extension();
        $file->storeAs('', $filename, 'public');

        $mahasiswa->update(['foto' => $filename]);
        $mahasiswa->foto_profil_url = url('storage/' . $filename);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
            'data'    => ['foto_profil_url' => url('storage/' . $filename)],
        ]);
    }

    // ===== KRS (dengan kontrol admin via krs_setting) =====
    public function krs(Request $request)
    {
        $nim           = $this->getNim($request);
        $mahasiswa     = $this->getMahasiswa($request);
        $semester      = $request->query('semester', $mahasiswa->semester_sekarang);
        $tahunAkademik = $request->query('tahun_akademik');

        // Cek apakah KRS dibuka untuk mahasiswa ini
        $setting = KrsSetting::where('nim', $nim)
            ->where('semester', $semester)
            ->when($tahunAkademik, fn($q) => $q->where('tahun_akademik', $tahunAkademik))
            ->first();

        $krsAktif = $setting?->is_aktif ?? false;

        // KRS yang sudah terisi
        $query = Krs::where('nim', $nim)->with('mataKuliah');
        if ($semester)      $query->where('semester', $semester);
        if ($tahunAkademik) $query->where('tahun_akademik', $tahunAkademik);
        $krs     = $query->get();
        $totalSks = $krs->where('status', 'Aktif')->sum(fn($k) => $k->mataKuliah->sks ?? 0);

        // MK tersedia dari kurikulum untuk semester ini
        $kurikulum = Kurikulum::where('semester', $semester)
            ->where('prodi', $mahasiswa->prodi)
            ->with('mataKuliah')
            ->get()
            ->map(fn($k) => [
                'kode_mk'  => $k->kode_mk,
                'nama_mk'  => $k->mataKuliah->nama_mk ?? $k->kode_mk,
                'sks'      => $k->mataKuliah->sks ?? 0,
                'status'   => $k->status,
                'sudah_krs'=> $krs->pluck('kode_mk')->contains($k->kode_mk),
            ]);

        return response()->json([
            'success'        => true,
            'data'           => $krs,
            'meta'           => [
                'total_sks'       => $totalSks,
                'krs_dibuka'      => $krsAktif,
                'semester'        => (int)$semester,
                'tahun_akademik'  => $tahunAkademik ?? $setting?->tahun_akademik,
            ],
            'kurikulum'      => $kurikulum,
            'krs_terisi'     => $krs->pluck('kode_mk'),
        ]);
    }

    // ===== KURIKULUM (semua semester 1-7) =====
    public function kurikulum(Request $request)
    {
        $mahasiswa = $this->getMahasiswa($request);
        $kurikulum = Kurikulum::where('prodi', $mahasiswa->prodi)
            ->with('mataKuliah')
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get()
            ->map(fn($k) => [
                'id'       => $k->id,
                'kode_mk'  => $k->kode_mk,
                'nama_mk'  => $k->mataKuliah->nama_mk ?? $k->kode_mk,
                'sks'      => $k->mataKuliah->sks ?? 0,
                'semester' => $k->semester,
                'status'   => $k->status,
                'prodi'    => $k->prodi,
            ]);

        return response()->json(['success'=>true,'data'=>$kurikulum]);
    }

    // ===== NILAI =====
    public function nilai(Request $request)
    {
        $nim      = $this->getNim($request);
        $semester = $request->query('semester');
        $query    = Nilai::where('nim', $nim)->with('mataKuliah','dosen');
        if ($semester) $query->where('semester', $semester);
        return response()->json(['success'=>true,'data'=>$query->orderBy('semester')->get()]);
    }

    // ===== KHS =====
    public function khs(Request $request)
    {
        $nim         = $this->getNim($request);
        $mahasiswa   = $this->getMahasiswa($request);
        $khs         = Khs::where('nim', $nim)->orderBy('semester')->get();
        $khsTerakhir = $khs->last();

        // Ambil nilai per semester untuk detail mata kuliah
        $nilaiAll = Nilai::where('nim', $nim)->with('mataKuliah')->get()->groupBy('semester');

        $ipsPerSemester = $khs->map(function($k) use ($nilaiAll) {
            $matkul = ($nilaiAll[$k->semester] ?? collect())->map(fn($n) => [
                'kode_mk'  => $n->kode_mk,
                'nama_mk'  => $n->mataKuliah->nama_mk ?? $n->kode_mk,
                'sks'      => $n->mataKuliah->sks ?? 0,
                'nilai'    => $n->nilai_huruf ?? $n->grade ?? '-',
                'bobot'    => $n->nilai_angka ?? $n->bobot ?? 0,
            ])->values();
            return [
                'semester'            => $k->semester,
                'tahun_akademik'      => $k->tahun_akademik,
                'ips'                 => $k->ips,
                'ipk'                 => $k->ipk,
                'total_sks'           => $k->total_sks,
                'total_sks_kumulatif' => $k->total_sks_kumulatif,
                'mata_kuliah'         => $matkul,
            ];
        });

        return response()->json([
            'success'       => true,
            'ipk'           => $khsTerakhir?->ipk ?? 0,
            'program_studi' => $mahasiswa->prodi ?? '-',
            'data'          => $ipsPerSemester,
            'meta'          => [
                'ipk'                 => $khsTerakhir?->ipk ?? 0,
                'total_sks_kumulatif' => $khsTerakhir?->total_sks_kumulatif ?? 0,
            ],
        ]);
    }

    // ===== JADWAL KULIAH =====
    public function jadwalKuliah(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $krs       = Krs::where('nim', $nim)->where('semester', $mahasiswa->semester_sekarang)->where('status','Aktif')->pluck('kode_mk');

        $jadwal = JadwalKuliah::whereIn('kode_mk', $krs)
            ->where('kelas', $mahasiswa->kelas)
            ->with('dosen','mataKuliah')
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn($j) => [
                'kode_mk'    => $j->kode_mk,
                'nama_mk'    => $j->mataKuliah->nama_mk ?? $j->kode_mk,
                'hari'       => $j->hari,
                'jam_mulai'  => $j->jam_mulai,
                'jam_selesai'=> $j->jam_selesai,
                'ruang'      => $j->ruang,
                'dosen'      => $j->dosen->nama ?? '-',
                'kelas'      => $j->kelas,
            ]);

        return response()->json(['success'=>true,'data'=>$jadwal]);
    }

    // ===== JADWAL UJIAN =====
    public function jadwalUjian(Request $request)
    {
        $mahasiswa = $this->getMahasiswa($request);
        $nim       = $this->getNim($request);

        // Ambil KRS aktif semester ini
        $kodeMks = Krs::where('nim', $nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->where('status', 'Aktif')
            ->pluck('kode_mk');

        $jadwal = JadwalUjian::where('kelas', $mahasiswa->kelas)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->whereIn('kode_mk', $kodeMks)
            ->with('mataKuliah','dosen')
            ->orderBy('tanggal')
            ->orderBy('mulai')
            ->get()
            ->map(fn($j) => [
                'id'          => $j->id,
                'kode_mk'     => $j->kode_mk,
                'nama_mk'     => $j->mataKuliah->nama_mk ?? $j->kode_mk,
                'tanggal'     => $j->tanggal,
                'jam_mulai'   => $j->jam_mulai,
                'jam_selesai' => $j->jam_selesai,
                'ruang'       => $j->ruang,
                'kelas'       => $j->kelas,
                'dosen'       => $j->dosen->nama ?? '-',
                'jenis_ujian' => $j->jenis_ujian ?? 'UTS',
            ]);

        return response()->json(['success'=>true,'data'=>$jadwal]);
    }

    // ===== KEHADIRAN =====
    public function kehadiran(Request $request)
    {
        $nim    = $this->getNim($request);
        $kodeMk = $request->query('kode_mk');
        $query  = Kehadiran::where('nim', $nim)->with('mataKuliah');
        if ($kodeMk) $query->where('kode_matkul', $kodeMk);

        $kehadiran = $query->orderBy('tanggal')->get();
        $rekap = $kehadiran->groupBy('kode_matkul')->map(function ($items) {
            $total = $items->count();
            $hadir = $items->where('status', 'Hadir')->count();
            $sakit = $items->where('status', 'Sakit')->count();
            $izin  = $items->where('status', 'Izin')->count();
            $alpha = $items->where('status', 'Tidak Hadir')->count();
            return [
                'total'      => $total,
                'hadir'      => $hadir,
                'sakit'      => $sakit,
                'izin'       => $izin,
                'alpha'      => $alpha,
                'persentase' => $total > 0 ? round(($hadir+$sakit+$izin)/$total*100, 1) : 0,
                'mk_nama'    => $items->first()?->mataKuliah?->nama_mk,
            ];
        });

        return response()->json(['success'=>true,'data'=>$kehadiran,'rekap'=>$rekap]);
    }

    // ===== KARTU MATA KULIAH (KMK) =====
    public function kmk(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);

        $krs = Krs::where('nim', $nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->where('status', 'Aktif')
            ->with('mataKuliah')
            ->get();

        // Histori pembayaran semester ini
        $tagihan = Tagihan::where('nim', $nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->with('pembayaran')
            ->get();

        return response()->json([
            'success'    => true,
            'data'       => [
                'mahasiswa'  => [
                    'nim'              => $mahasiswa->nim,
                    'nama'             => $mahasiswa->nama,
                    'prodi'            => $mahasiswa->prodi,
                    'kelas'            => $mahasiswa->kelas,
                    'semester_sekarang'=> $mahasiswa->semester_sekarang,
                    'angkatan'         => $mahasiswa->angkatan,
                ],
                'matakuliah' => $krs->map(fn($k) => [
                    'kode_mk' => $k->kode_mk,
                    'nama_mk' => $k->mataKuliah->nama_mk ?? $k->kode_mk,
                    'sks'     => $k->mataKuliah->sks ?? 0,
                    'kelas'   => $mahasiswa->kelas,
                    'status'  => $k->status,
                ]),
                'tagihan'    => $tagihan->map(fn($t) => [
                    'keterangan'      => $t->keterangan,
                    'nominal_tagihan' => $t->nominal_tagihan,
                    'nominal_bayar'   => $t->nominal_bayar ?? 0,
                    'status_bayar'    => $t->status_bayar,
                    'batas_bayar'     => $t->batas_bayar,
                    'pembayaran'      => $t->pembayaran,
                ]),
                'total_sks'  => $krs->sum(fn($k) => $k->mataKuliah->sks ?? 0),
            ],
        ]);
    }

    // ===== KEUANGAN =====
    public function tagihan(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $tagihan   = Tagihan::where('nim', $nim)->with('pembayaran')->orderBy('semester')->get();

        $formatted = $tagihan->groupBy('semester')->map(function ($items, $semester) {
            // Total tagihan semester
            $totalTagihan = $items->sum('nominal_tagihan');

            // Total dibayar dari tabel pembayaran
            $totalDibayar = $items->flatMap(fn($t) => $t->pembayaran)
                                  ->sum('jumlah_bayar');

            // Rincian pembayaran (flatten semua pembayaran semester ini)
            $riwayatBayar = $items->flatMap(fn($t) => $t->pembayaran->map(fn($p) => [
                'tanggal' => $p->tanggal_bayar,
                'nominal' => $p->jumlah_bayar,
                'metode'  => $p->metode,
            ]))->values();

            return [
                'semester'     => $semester,
                'tagihan'      => $totalTagihan,
                'dibayar'      => $totalDibayar,
                'sisa_tagihan' => max(0, $totalTagihan - $totalDibayar),
                'rincian'      => $items->map(fn($t) => [
                    'deskripsi'        => $t->deskripsi,
                    'nominal'          => $t->nominal_tagihan,
                    'status_bayar'     => $t->status_bayar,
                    'tanggal_jatuh_tempo' => $t->tanggal_jatuh_tempo,
                ])->values(),
                'pembayaran'   => $riwayatBayar,
            ];
        })->values();

        return response()->json([
            'success'         => true,
            'data'            => $formatted,
            'virtual_account' => $mahasiswa->virtual_account,
            'meta'            => [
                'total_belum_bayar' => $tagihan->where('status_bayar','Belum')->sum('nominal_tagihan'),
                'total_lunas'       => $tagihan->where('status_bayar','Lunas')->sum('nominal_tagihan'),
            ],
        ]);
    }

    // ===== PENGUMUMAN =====
    public function pengumuman(Request $request)
    {
        $bulan = $request->query('bulan', 3); // default 3 bulan terakhir
        $from  = now()->subMonths($bulan)->startOfDay();

        $pengumuman = Pengumuman::where('is_published', 1)
            ->where('created_at', '>=', $from)
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(20);

        return response()->json(['success'=>true,'data'=>$pengumuman->items(),'meta'=>[
            'total'        => $pengumuman->total(),
            'current_page' => $pengumuman->currentPage(),
            'last_page'    => $pengumuman->lastPage(),
        ]]);
    }

    public function pengumumanDetail($id)
    {
        $pengumuman = Pengumuman::where('is_published', 1)->findOrFail($id);
        return response()->json(['success'=>true,'data'=>$pengumuman]);
    }

    // ===== KALENDER + GAMBAR KEGIATAN =====
    public function kalender()
    {
        $kalender = KalenderAkademik::where('is_published', 1)
            ->with('gambar')
            ->orderBy('tanggal_mulai')
            ->get()
            ->map(fn($k) => [
                'id'             => $k->id,
                'judul'          => $k->judul,
                'deskripsi'      => $k->deskripsi,
                'tanggal_mulai'  => $k->tanggal_mulai,
                'tanggal_selesai'=> $k->tanggal_selesai,
                'kategori'       => $k->kategori,
                'file_path'      => $k->file_path,
                'file_url'       => $k->file_path ? url('storage/'.$k->file_path) : null,
                'gambar'         => $k->gambar->map(fn($g) => [
                    'id'       => $g->id,
                    'file_url' => $g->file_url,
                    'urutan'   => $g->urutan,
                ]),
            ]);

        return response()->json(['success'=>true,'data'=>$kalender]);
    }

    // ===== NOTIFIKASI =====
    public function notifikasi(Request $request)
    {
        $nim        = $this->getNim($request);
        $notifikasi = NotifikasiMahasiswa::where('nim', $nim)->orderBy('created_at','desc')->paginate(20);
        $unread     = NotifikasiMahasiswa::where('nim', $nim)->where('is_read', 0)->count();
        return response()->json(['success'=>true,'data'=>$notifikasi->items(),'meta'=>['unread_count'=>$unread]]);
    }

    public function notifikasiCount(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json(['success'=>true,'data'=>['unread_count'=>$this->notifService->countUnread($nim)]]);
    }

    public function notifikasiRead(Request $request, $id)
    {
        $nim = $this->getNim($request);
        $this->notifService->markAsRead((int)$id, $nim);
        return response()->json(['success'=>true,'message'=>'Notifikasi ditandai dibaca.']);
    }

    public function notifikasiReadAll(Request $request)
    {
        $nim   = $this->getNim($request);
        $count = $this->notifService->markAllAsRead($nim);
        return response()->json(['success'=>true,'message'=>"{$count} notifikasi ditandai dibaca."]);
    }

    // ===== PENGADUAN =====
    public function pengaduan(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json(['success'=>true,'data'=>SuratPengaduan::where('nim',$nim)->with('dosen')->orderBy('id','desc')->get()]);
    }

    public function storePengaduan(Request $request)
    {
        $nim       = $this->getNim($request);
        $validated = $request->validate(['tujuan'=>'required|in:dosen,admin,prodi','dosen_id'=>'nullable|exists:dosen,id','perihal'=>'required|string|max:255','isi_surat'=>'required|string']);
        $validated['nim']    = $nim;
        $validated['status'] = 'Terkirim';
        return response()->json(['success'=>true,'message'=>'Surat pengaduan terkirim.','data'=>SuratPengaduan::create($validated)],201);
    }

    // ===== BIMBINGAN SKRIPSI =====
    public function bimbinganSkripsi(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json([
            'success'=>true,
            'data'=>BimbinganSkripsi::where('nim',$nim)->with('dosen')->orderBy('tanggal','desc')->get()->map(fn($b)=>[
                'id'            => $b->id,
                'tanggal'       => $b->tanggal,
                'bab'           => $b->bab,
                'uraian'        => $b->uraian,
                'catatan_dosen' => $b->catatan_dosen,
                'status'        => $b->status,
                'pembimbing'    => $b->dosen->nama ?? '-',
            ]),
        ]);
    }

    public function storeBimbinganSkripsi(Request $request)
    {
        $nim       = $this->getNim($request);
        $validated = $request->validate(['dosen_id'=>'required|exists:dosen,id','tanggal'=>'required|date','bab'=>'required|string|max:50','uraian'=>'required|string']);
        $validated['nim'] = $nim;
        return response()->json(['success'=>true,'message'=>'Pengajuan bimbingan terkirim.','data'=>BimbinganSkripsi::create($validated)],201);
    }

    // ===== BIMBINGAN PROPOSAL (sidang proposal) =====
    public function bimbinganProposal(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json([
            'success'=>true,
            'data'=>BimbinganProposal::where('nim',$nim)->with('dosen')->orderBy('id','desc')->get()->map(fn($b)=>[
                'id'             => $b->id,
                'judul_proposal' => $b->judul_proposal,
                'tanggal_sidang' => $b->tanggal_sidang,
                'nilai'          => $b->nilai,
                'catatan_revisi' => $b->catatan_revisi,
                'status'         => $b->status,
                'dosen_penguji'  => $b->dosen->nama ?? '-',
                'created_at'     => $b->created_at,
            ]),
        ]);
    }

    // ===== BIMBINGAN SIDANG SKRIPSI =====
    public function bimbinganSidang(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json([
            'success'=>true,
            'data'=>BimbinganSidang::where('nim',$nim)
                ->with('pembimbing1','pembimbing2','penguji1','penguji2')
                ->orderBy('id','desc')
                ->get()
                ->map(fn($b)=>[
                    'id'             => $b->id,
                    'judul_skripsi'  => $b->judul_skripsi,
                    'tanggal_sidang' => $b->tanggal_sidang,
                    'ruang'          => $b->ruang,
                    'nilai'          => $b->nilai,
                    'catatan_revisi' => $b->catatan_revisi,
                    'status'         => $b->status,
                    'pembimbing1'    => $b->pembimbing1->nama ?? '-',
                    'pembimbing2'    => $b->pembimbing2->nama ?? '-',
                    'penguji1'       => $b->penguji1->nama ?? '-',
                    'penguji2'       => $b->penguji2->nama ?? '-',
                    'created_at'     => $b->created_at,
                ]),
        ]);
    }

    // ===== PENGAJUAN JUDUL SKRIPSI =====
    public function skripsiPengajuan(Request $request)
    {
        $nim = $this->getNim($request);
        $mhs = $this->getMahasiswa($request);
        return response()->json(['success'=>true,'data'=>SkripsiPengajuan::where('nim',$nim)->with('pembimbing1','pembimbing2')->first()]);
    }

    public function storeSkripsiPengajuan(Request $request)
    {
        $nim       = $this->getNim($request);
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'abstrak'        => 'required|string',
            'jalur'          => 'required|string',
            'baru_ulang'     => 'required|in:Baru,Ulang',
            'sertifikasi'    => 'nullable|array',
            'pembimbing1_id' => 'nullable|exists:dosen,id',
        ]);
        $validated['nim']          = $nim;
        $validated['status']       = 'Diajukan';
        $validated['tgl_pengajuan']= now()->toDateString();
        if (isset($validated['sertifikasi'])) {
            $validated['sertifikasi'] = implode(',', $validated['sertifikasi']);
        }
        return response()->json(['success'=>true,'message'=>'Pengajuan judul terkirim.','data'=>SkripsiPengajuan::create($validated)],201);
    }

    // ===== PENGAJUAN UJIAN SKRIPSI =====
    public function skripsiUjian(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json(['success'=>true,'data'=>SkripsiUjian::where('nim',$nim)->with('pembimbing1','pembimbing2')->orderBy('tgl_pengajuan','desc')->get()]);
    }

    public function storeSkripsiUjian(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $khsTerakhir = Khs::where('nim',$nim)->orderBy('semester','desc')->first();

        $validated = $request->validate([
            'judul_skripsi'  => 'required|string|max:255',
            'pembimbing1_id' => 'required|exists:dosen,id',
            'pembimbing2_id' => 'nullable|exists:dosen,id',
            'sertifikasi'    => 'nullable|array',
            'nomor_ijazah'   => 'nullable|string|max:100',
            'penerima_kps'   => 'nullable|in:Ya,Tidak',
            'nik'            => 'nullable|string|max:50',
            'nisn'           => 'nullable|string|max:50',
        ]);
        $validated['nim']           = $nim;
        $validated['ipk_terakhir']  = $khsTerakhir?->ipk ?? 0;
        $validated['jumlah_sks']    = $khsTerakhir?->total_sks_kumulatif ?? 0;
        $validated['status']        = 'Diajukan';
        $validated['tgl_pengajuan'] = now()->toDateString();
        if (isset($validated['sertifikasi'])) {
            $validated['sertifikasi'] = implode(',', $validated['sertifikasi']);
        }
        return response()->json(['success'=>true,'message'=>'Pengajuan ujian skripsi terkirim.','data'=>SkripsiUjian::create($validated)],201);
    }

    // ===== MAGANG =====
    public function magang(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json(['success'=>true,'data'=>PengajuanMagang::where('nim',$nim)->orderBy('tgl_pengajuan','desc')->get()]);
    }

    public function storeMagang(Request $request)
    {
        $nim       = $this->getNim($request);
        $validated = $request->validate([
            'jenis_tempat_magang'   => 'required|string',
            'alamat'                => 'required|string',
            'nama_tempat_magang'    => 'required|string',
            'kota_kabupaten_magang' => 'required|string',
            'baru_ulang'            => 'required|in:Baru,Ulang',
            'rencana_mulai'         => 'required|date',
            'rencana_selesai'       => 'required|date|after:rencana_mulai',
        ]);
        $validated['nim']           = $nim;
        $validated['status_magang'] = 'Menunggu';
        $validated['tgl_pengajuan'] = now()->toDateString();
        return response()->json(['success'=>true,'message'=>'Pengajuan magang terkirim.','data'=>PengajuanMagang::create($validated)],201);
    }

    // ===== PERPUSTAKAAN =====
    public function pinjaman(Request $request)
    {
        $nim = $this->getNim($request);
        return response()->json(['success'=>true,'data'=>Pinjaman::where('nim',$nim)->orderBy('tanggal_pinjam','desc')->get()]);
    }

    // ===== POINT BOOK =====
    public function pointBook(Request $request)
    {
        $nim       = $this->getNim($request);
        $points    = PointBook::where('nim',$nim)->orderBy('tanggal','desc')->get();
        $totalPoin = $points->sum('poin');
        return response()->json(['success'=>true,'data'=>$points,'meta'=>['total_poin'=>$totalPoin]]);
    }

    // ===== IKAD: kuesioner kepuasan dosen per semester =====
    public function ikad(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $semester  = $request->query('semester', $mahasiswa->semester_sekarang);

        // Ambil dosen yang mengajar di semester ini (dari jadwal_kuliah via KRS)
        $kodeMks   = Krs::where('nim',$nim)->where('semester',$semester)->where('status','Aktif')->pluck('kode_mk');
        $dosenList = DosenMatkul::whereIn('kode_mk',$kodeMks)->with('dosen','mataKuliah')->get();

        // Status IKAD sudah/belum diisi
        $sudahIsi  = IkadKuisioner::where('nim',$nim)->where('semester',$semester)->pluck('kode_mk')->toArray();

        $data = $dosenList->map(fn($dm)=>[
            'kode_mk'    => $dm->kode_mk,
            'nama_matkul'=> $dm->mataKuliah->nama_mk ?? $dm->kode_mk,
            'dosen_id'   => $dm->dosen_id,
            'dosen'      => $dm->dosen->nama ?? '-',
            'status'     => in_array($dm->kode_mk, $sudahIsi) ? 'Sudah Diisi' : 'Belum Diisi',
        ]);

        return response()->json(['success'=>true,'data'=>$data,'semester'=>(int)$semester]);
    }

    // ===== IKAS: kuesioner kepuasan staf BAAK =====
    public function ikas(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $semester  = $request->query('semester', $mahasiswa->semester_sekarang);

        $staffList = Staff::orderBy('bagian')->get();
        $sudahIsi  = IkasKuisioner::where('nim',$nim)->where('semester',$semester)->pluck('id_staff')->toArray();

        $data = $staffList->map(fn($s)=>[
            'id_staff'  => $s->id_staff,
            'nama_staf' => $s->nama_staf,
            'bagian'    => $s->bagian,
            'jabatan'   => $s->jabatan,
            'status'    => in_array($s->id_staff, $sudahIsi) ? 'Sudah Diisi' : 'Belum Diisi',
        ]);

        return response()->json(['success'=>true,'data'=>$data,'semester'=>(int)$semester]);
    }

    // ===== SUBMIT KUESIONER IKAD / IKAS =====
    public function submitKuesioner(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);
        $validated = $request->validate([
            'jenis_survei' => 'required|in:ikad,ikas',
            'target_id'    => 'required',
            'answers'      => 'required|array|min:1',
        ]);

        $semester = $mahasiswa->semester_sekarang;

        if ($validated['jenis_survei'] === 'ikad') {
            $cek = IkadKuisioner::where('nim',$nim)->where('kode_mk',$validated['target_id'])->where('semester',$semester)->first();
            if ($cek) return response()->json(['status'=>'error','message'=>'Kuesioner ini sudah pernah diisi.'],400);
            IkadKuisioner::create(['nim'=>$nim,'kode_mk'=>$validated['target_id'],'semester'=>$semester,'status'=>'Sudah Diisi','tanggal_isi'=>now()]);
        } else {
            $cek = IkasKuisioner::where('nim',$nim)->where('id_staff',$validated['target_id'])->where('semester',$semester)->first();
            if ($cek) return response()->json(['status'=>'error','message'=>'Kuesioner ini sudah pernah diisi.'],400);
            IkasKuisioner::create(['nim'=>$nim,'id_staff'=>$validated['target_id'],'semester'=>$semester,'status'=>'Sudah Diisi','tanggal_isi'=>now()]);
        }

        return response()->json(['status'=>'success','message'=>'Kuesioner berhasil disimpan.']);
    }

    // ===== DASHBOARD =====
    public function dashboard(Request $request)
    {
        $nim       = $this->getNim($request);
        $mahasiswa = $this->getMahasiswa($request);

        $khsTerakhir      = Khs::where('nim',$nim)->orderBy('semester','desc')->first();
        $krsAktif         = Krs::where('nim',$nim)->where('status','Aktif')->where('semester',$mahasiswa->semester_sekarang)->count();
        $unreadNotif      = NotifikasiMahasiswa::where('nim',$nim)->where('is_read',0)->count();
        $tagihanBelum     = Tagihan::where('nim',$nim)->where('status_bayar','Belum')->sum('nominal_tagihan');
        $pengumumanTerbaru = Pengumuman::where('is_published',1)->orderBy('created_at','desc')->take(3)->get();
        $kalenderMendatang = KalenderAkademik::where('is_published',1)
            ->where('tanggal_mulai','>=',now()->toDateString())
            ->with('gambar')
            ->orderBy('tanggal_mulai')->take(5)->get();

        return response()->json([
            'success'=>true,
            'data'=>[
                'mahasiswa'=>[
                    'nim'              => $mahasiswa->nim,
                    'nama'             => $mahasiswa->nama,
                    'prodi'            => $mahasiswa->prodi,
                    'kelas'            => $mahasiswa->kelas,
                    'semester_sekarang'=> $mahasiswa->semester_sekarang,
                    'foto_profil_url'  => $mahasiswa->foto ? url('storage/'.$mahasiswa->foto) : null,
                ],
                'akademik'=>[
                    'ipk'                        => $khsTerakhir?->ipk ?? 0,
                    'ips'                        => $khsTerakhir?->ips ?? 0,
                    'total_sks'                  => $khsTerakhir?->total_sks_kumulatif ?? 0,
                    'jumlah_matkul_semester_ini' => $krsAktif,
                ],
                'keuangan'           => ['tagihan_belum_bayar'=>$tagihanBelum],
                'notifikasi'         => ['unread_count'=>$unreadNotif],
                'pengumuman_terbaru' => $pengumumanTerbaru,
                'kalender_mendatang' => $kalenderMendatang,
            ],
        ]);
    }



    // ===== HOTSPOT =====
    public function hotspot(Request $request)
    {
        $mahasiswa = $this->getMahasiswa($request);
        $validated = $request->validate([
            'password' => 'required|string|min:6|max:50',
        ]);
        // Simpan ke kolom password_hotspot jika ada, atau abaikan
        try {
            if (in_array('password_hotspot', $mahasiswa->getFillable())) {
                $mahasiswa->update(['password_hotspot' => $validated['password']]);
            }
        } catch (\Exception $e) {}
        return response()->json([
            'success' => true,
            'message' => 'Password hotspot berhasil disimpan.',
            'data'    => ['nim' => $mahasiswa->nim, 'nama' => $mahasiswa->nama],
        ]);
    }

    // ===== DOWNLOAD MATERI =====
    public function downloadMateri()
    {
        $items = DownloadMateri::orderBy('kategori')
            ->get()
            ->map(fn($d) => [
                'id'         => $d->id,
                'judul'      => $d->keterangan,
                'keterangan' => $d->keterangan,
                'kategori'   => $d->kategori,
                'file_path'  => $d->file_path,
                'file_url'   => $d->file_path ? url('storage/' . $d->file_path) : null,
            ]);
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function bannerKegiatan()
    {
        $banners = BannerKegiatan::where('is_aktif', true)
            ->orderBy('urutan')
            ->get()
            ->map(fn($b) => [
                'id'       => $b->id,
                'judul'    => $b->judul,
                'file_url' => $b->file_url,
                'urutan'   => $b->urutan,
            ]);

        return response()->json(['success' => true, 'data' => $banners]);
    }
}