<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\DosenMatkul;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();
        if ($s = $request->search) {
            $query->where(fn($q) => $q->where('kode_mk','like',"%$s%")->orWhere('nama_mk','like',"%$s%"));
        }
        if ($request->semester) $query->where('semester', $request->semester);
        $matakuliahs = $query->orderBy('semester')->orderBy('kode_mk')->paginate(25)->withQueryString();
        return view('admin.matakuliah.index', compact('matakuliahs'));
    }

    public function create()
    {
        return view('admin.matakuliah.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'kode_mk'  => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'prodi'    => 'nullable|string|max:100',
            'deskripsi'=> 'nullable|string',
        ]);
        MataKuliah::create($v);
        return redirect()->route('admin.matakuliah.index')->with('success','Mata kuliah ditambahkan.');
    }

    public function edit($kode)
    {
        $mk = MataKuliah::findOrFail($kode);
        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        $pengampus = DosenMatkul::where('kode_mk',$kode)->with('dosen')->get();
        return view('admin.matakuliah.edit', compact('mk','dosenList','pengampus'));
    }

    public function update(Request $request, $kode)
    {
        $mk = MataKuliah::findOrFail($kode);
        $v = $request->validate([
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'prodi'    => 'nullable|string|max:100',
            'deskripsi'=> 'nullable|string',
        ]);
        $mk->update($v);
        return redirect()->route('admin.matakuliah.index')->with('success','Mata kuliah diperbarui.');
    }

    public function destroy($kode)
    {
        MataKuliah::findOrFail($kode)->delete();
        return redirect()->route('admin.matakuliah.index')->with('success','Mata kuliah dihapus.');
    }

    // Assign dosen ke mata kuliah
    public function assignDosen(Request $request, $kode)
    {
        $v = $request->validate([
            'dosen_id'       => 'required|exists:dosen,id',
            'tahun_akademik' => 'required|string',
            'semester'       => 'required|integer',
            'kelas'          => 'required|string|max:20',
        ]);
        $v['kode_mk'] = $kode;
        DosenMatkul::updateOrCreate(
            ['dosen_id'=>$v['dosen_id'],'kode_mk'=>$kode,'tahun_akademik'=>$v['tahun_akademik'],'kelas'=>$v['kelas']],
            $v
        );
        return redirect()->back()->with('success','Dosen pengampu ditambahkan.');
    }

    public function removeDosen($id)
    {
        DosenMatkul::findOrFail($id)->delete();
        return redirect()->back()->with('success','Dosen pengampu dihapus.');
    }
}
