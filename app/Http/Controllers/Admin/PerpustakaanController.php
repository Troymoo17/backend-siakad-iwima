<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PerpustakaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with('mahasiswa');
        if ($nim = $request->nim)     $query->where('nim','like',"%$nim%");
        if ($request->status_pinjaman) $query->where('status_pinjaman',$request->status_pinjaman);
        $pinjamans = $query->orderBy('tanggal_pinjam','desc')->paginate(25)->withQueryString();
        return view('admin.perpustakaan.index', compact('pinjamans'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nim'            => 'required|exists:mahasiswa,nim',
            'nama_buku'      => 'required|string|max:255',
            'kode_buku'      => 'nullable|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali'=> 'required|date|after:tanggal_pinjam',
        ]);
        $v['status_pinjaman'] = 'Dipinjam';
        Pinjaman::create($v);
        return redirect()->back()->with('success','Data pinjaman ditambahkan.');
    }

    public function kembalikan(Request $request, $id)
    {
        $p = Pinjaman::findOrFail($id);
        $terlambat = now()->toDateString() > $p->tanggal_kembali;
        $denda = 0;
        if ($terlambat) {
            $hari = now()->diffInDays($p->tanggal_kembali);
            $denda = $hari * 1000; // Rp 1.000/hari
        }
        $p->update([
            'status_pinjaman'        => $terlambat ? 'Terlambat' : 'Sudah Kembali',
            'tanggal_kembali_aktual' => now()->toDateString(),
            'denda'                  => $denda,
        ]);
        $msg = 'Buku berhasil dikembalikan.';
        if ($denda > 0) $msg .= ' Denda: Rp '.number_format($denda,0,',','.');
        return redirect()->back()->with('success', $msg);
    }

    public function destroy($id)
    {
        Pinjaman::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data pinjaman dihapus.');
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }
}
