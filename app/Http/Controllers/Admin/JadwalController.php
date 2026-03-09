<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKuliah::with('dosen','mataKuliah');
        if ($s = $request->search) $query->where(fn($q) => $q->where('nama_mk','like',"%$s%")->orWhere('kelas','like',"%$s%")->orWhere('kode_mk','like',"%$s%"));
        if ($request->kelas)          $query->where('kelas',$request->kelas);
        if ($request->hari)           $query->where('hari',$request->hari);
        if ($request->tahun_akademik) $query->where('tahun_akademik',$request->tahun_akademik);
        $jadwals = $query->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")->orderBy('jam_mulai')->paginate(25)->withQueryString();
        $mkList    = MataKuliah::orderBy('semester')->get();
        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        return view('admin.jadwal.index', compact('jadwals','mkList','dosenList'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'kode_mk'           => 'required|exists:mata_kuliah,kode_mk',
            'dosen_id'          => 'nullable|exists:dosen,id',
            'kelas'             => 'required|string|max:20',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required',
            'ruang'             => 'required|string|max:50',
            'jenis'             => 'required|in:Teori,Praktikum',
            'semester'          => 'required|integer',
            'tahun_akademik'    => 'required|string',
            'google_classroom_id' => 'nullable|string|max:255',
        ]);
        $mk = MataKuliah::find($v['kode_mk']);
        $v['nama_mk'] = $mk->nama_mk;
        JadwalKuliah::create($v);
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal kuliah ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal    = JadwalKuliah::findOrFail($id);
        $mkList    = MataKuliah::orderBy('semester')->get();
        $dosenList = Dosen::where('is_active',1)->orderBy('nama')->get();
        return view('admin.jadwal.edit', compact('jadwal','mkList','dosenList'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $v = $request->validate([
            'kode_mk'           => 'required|exists:mata_kuliah,kode_mk',
            'dosen_id'          => 'nullable|exists:dosen,id',
            'kelas'             => 'required|string|max:20',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required',
            'ruang'             => 'required|string|max:50',
            'jenis'             => 'required|in:Teori,Praktikum',
            'semester'          => 'required|integer',
            'tahun_akademik'    => 'required|string',
            'google_classroom_id' => 'nullable|string|max:255',
        ]);
        $mk = MataKuliah::find($v['kode_mk']);
        $v['nama_mk'] = $mk->nama_mk;
        $jadwal->update($v);
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal diperbarui.');
    }

    public function destroy($id)
    {
        JadwalKuliah::findOrFail($id)->delete();
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal dihapus.');
    }
}
