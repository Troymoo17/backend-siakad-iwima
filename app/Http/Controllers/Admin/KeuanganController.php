<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Mahasiswa;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = Tagihan::with('mahasiswa','pembayaran');
        if ($nim = $request->nim)    $query->where('nim','like',"%$nim%");
        if ($request->status_bayar)  $query->where('status_bayar',$request->status_bayar);
        if ($request->jenis_tagihan) $query->where('jenis_tagihan',$request->jenis_tagihan);
        if ($request->semester)      $query->where('semester',$request->semester);
        $tagihans = $query->orderBy('nim')->orderBy('semester')->paginate(25)->withQueryString();

        $stats = [
            'total_belum' => Tagihan::where('status_bayar','Belum')->sum('nominal_tagihan'),
            'total_lunas' => Tagihan::where('status_bayar','Lunas')->sum('nominal_tagihan'),
            'count_belum' => Tagihan::where('status_bayar','Belum')->count(),
        ];
        return view('admin.keuangan.index', compact('tagihans','stats'));
    }

    public function storeTagihan(Request $request)
    {
        $v = $request->validate([
            'nim'                 => 'required|exists:mahasiswa,nim',
            'semester'            => 'required|integer|min:1|max:14',
            'tahun_akademik'      => 'required|string',
            'jenis_tagihan'       => 'required|in:UKP,SKS,Denda,Lainnya',
            'deskripsi'           => 'nullable|string|max:255',
            'nominal_tagihan'     => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date',
        ]);
        $v['status_bayar'] = 'Belum';
        $tagihan = Tagihan::create($v);

        // Notifikasi
        $this->notif->notifyTagihanBaru($v['nim'], $v['jenis_tagihan'], (float)$v['nominal_tagihan'], $v['semester']);

        return redirect()->back()->with('success','Tagihan ditambahkan & notifikasi dikirim.');
    }

    public function storePembayaran(Request $request)
    {
        $v = $request->validate([
            'tagihan_id'  => 'required|exists:tagihan,id',
            'nim'         => 'required|exists:mahasiswa,nim',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:1',
            'metode'        => 'required|in:Transfer,Virtual Account,Tunai,Lainnya',
            'keterangan'    => 'nullable|string|max:255',
            'bukti_bayar'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = time().'_bukti_'.$v['nim'].'.'.$file->extension();
            $file->storeAs('pembayaran', $filename, 'public');
            $v['bukti_bayar'] = $filename;
        }

        Pembayaran::create($v);

        // Update status tagihan
        $tagihan = Tagihan::find($v['tagihan_id']);
        $totalBayar = Pembayaran::where('tagihan_id',$v['tagihan_id'])->sum('jumlah_bayar');
        if ($totalBayar >= $tagihan->nominal_tagihan) {
            $tagihan->update(['status_bayar' => 'Lunas']);
        } else {
            $tagihan->update(['status_bayar' => 'Cicilan']);
        }

        return redirect()->back()->with('success','Pembayaran berhasil dicatat.');
    }

    public function destroyTagihan($id)
    {
        Tagihan::findOrFail($id)->delete();
        return redirect()->back()->with('success','Tagihan dihapus.');
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }

    public function generateTagihanBulk(Request $request)
    {
        $request->validate([
            'tahun_akademik'      => 'required|string',
            'semester'            => 'required|integer',
            'jenis_tagihan'       => 'required|in:UKP,SKS,Denda,Lainnya',
            'nominal_tagihan'     => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'kelas'               => 'nullable|string',
        ]);
        $mahasiswas = Mahasiswa::where('status_aktif','Aktif');
        if ($request->kelas) $mahasiswas->where('kelas',$request->kelas);
        $list = $mahasiswas->get();
        $count = 0;
        foreach ($list as $m) {
            $exists = Tagihan::where('nim',$m->nim)->where('semester',$request->semester)
                              ->where('jenis_tagihan',$request->jenis_tagihan)->exists();
            if (!$exists) {
                Tagihan::create([
                    'nim'                 => $m->nim,
                    'semester'            => $request->semester,
                    'tahun_akademik'      => $request->tahun_akademik,
                    'jenis_tagihan'       => $request->jenis_tagihan,
                    'deskripsi'           => $request->deskripsi ?? $request->jenis_tagihan.' Semester '.$request->semester,
                    'nominal_tagihan'     => $request->nominal_tagihan,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                    'status_bayar'        => 'Belum',
                ]);
                $this->notif->notifyTagihanBaru($m->nim, $request->jenis_tagihan, (float)$request->nominal_tagihan, $request->semester);
                $count++;
            }
        }
        return redirect()->back()->with('success',"Tagihan berhasil dibuat untuk $count mahasiswa.");
    }
}
