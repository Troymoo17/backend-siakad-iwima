<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::withCount('mahasiswaBimbingan');
        if ($s = $request->search) {
            $query->where(fn($q) => $q->where('nidn','like',"%$s%")->orWhere('nama','like',"%$s%")->orWhere('email','like',"%$s%"));
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->is_active !== null && $request->is_active !== '') $query->where('is_active', $request->is_active);
        $dosens = $query->orderBy('nama')->paginate(20)->withQueryString();
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nidn'             => 'required|string|max:20|unique:dosen,nidn',
            'nppy'             => 'nullable|string|max:20',
            'nama'             => 'required|string|max:255',
            'password'         => 'required|string|min:6',
            'gelar_depan'      => 'nullable|string|max:50',
            'gelar_belakang'   => 'nullable|string|max:100',
            'prodi'            => 'nullable|string|max:100',
            'jabatan_akademik' => 'nullable|string|max:100',
            'status'           => 'required|in:Tetap,Tidak Tetap,Luar Biasa',
            'email'            => 'nullable|email|unique:dosen,email',
            'telp'             => 'nullable|string|max:20',
        ]);
        $v['password'] = Hash::make($v['password']);
        $v['is_active'] = 1;
        Dosen::create($v);
        return redirect()->route('admin.dosen.index')->with('success','Dosen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $v = $request->validate([
            'nama'             => 'required|string|max:255',
            'gelar_depan'      => 'nullable|string|max:50',
            'gelar_belakang'   => 'nullable|string|max:100',
            'prodi'            => 'nullable|string|max:100',
            'jabatan_akademik' => 'nullable|string|max:100',
            'status'           => 'required|in:Tetap,Tidak Tetap,Luar Biasa',
            'email'            => 'nullable|email|unique:dosen,email,'.$id,
            'telp'             => 'nullable|string|max:20',
            'is_active'        => 'boolean',
        ]);
        if ($request->filled('password')) {
            $v['password'] = Hash::make($request->password);
        }
        $v['is_active'] = $request->boolean('is_active', true);
        $dosen->update($v);
        return redirect()->route('admin.dosen.index')->with('success','Data dosen diperbarui.');
    }

    public function destroy($id)
    {
        Dosen::findOrFail($id)->delete();
        return redirect()->route('admin.dosen.index')->with('success','Dosen dihapus.');
    }

    public function show($id)
    {
        $dosen = Dosen::with('mahasiswaBimbingan','mataKuliah.mataKuliah','jadwalKuliah')->findOrFail($id);
        return view('admin.dosen.show', compact('dosen'));
    }
}
