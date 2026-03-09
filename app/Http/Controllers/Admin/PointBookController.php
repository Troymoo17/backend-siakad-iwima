<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointBook;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PointBookController extends Controller
{
    public function index(Request $request)
    {
        $query = PointBook::with('mahasiswa');
        if ($nim = $request->nim) $query->where('nim','like',"%$nim%");
        $points = $query->orderBy('tanggal','desc')->paginate(25)->withQueryString();

        $rekapNim = null;
        if ($request->nim) {
            $rekapNim = PointBook::where('nim',$request->nim)->sum('poin');
        }
        return view('admin.pointbook.index', compact('points','rekapNim'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nim'           => 'required|exists:mahasiswa,nim',
            'tanggal'       => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'poin'          => 'required|integer|min:1',
            'keterangan'    => 'nullable|string|max:255',
        ]);
        $v['diinput_oleh'] = Session::get('admin_id');
        PointBook::create($v);
        return redirect()->back()->with('success','Poin kegiatan ditambahkan.');
    }

    public function destroy($id)
    {
        PointBook::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data poin dihapus.');
    }

    public function searchMahasiswa(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang']);
        return response()->json(['success'=>(bool)$mhs,'data'=>$mhs]);
    }
}
