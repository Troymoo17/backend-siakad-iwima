<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        $query = Kurikulum::with('mataKuliah');
        if ($request->semester) $query->where('semester', $request->semester);
        if ($request->prodi)    $query->where('prodi', $request->prodi);
        if ($request->status)   $query->where('status', $request->status);
        $kurikulums = $query->orderBy('semester')->orderBy('urutan')->paginate(30)->withQueryString();
        $mkList = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();
        return view('admin.kurikulum.index', compact('kurikulums','mkList'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'kode_mk'         => 'required|exists:mata_kuliah,kode_mk',
            'semester'        => 'required|integer|min:1|max:14',
            'prodi'           => 'required|string|max:100',
            'status'          => 'required|in:Wajib,Pilihan',
            'ipk_min'         => 'nullable|numeric|min:0|max:4',
            'sks_min'         => 'nullable|integer|min:0',
            'grade_min'       => 'nullable|string|max:2',
            'mk_persyaratan'  => 'nullable|string|max:20',
            'urutan'          => 'nullable|integer|min:1',
        ]);
        $v['updated_by'] = Session::get('admin_id');
        Kurikulum::updateOrCreate(
            ['kode_mk' => $v['kode_mk'], 'prodi' => $v['prodi']],
            $v
        );
        return redirect()->route('admin.kurikulum.index')->with('success','Kurikulum disimpan.');
    }

    public function edit($id)
    {
        $kurikulum = Kurikulum::with('mataKuliah')->findOrFail($id);
        $mkList = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();
        return view('admin.kurikulum.edit', compact('kurikulum','mkList'));
    }

    public function update(Request $request, $id)
    {
        $k = Kurikulum::findOrFail($id);
        $v = $request->validate([
            'semester'       => 'required|integer|min:1|max:14',
            'prodi'          => 'required|string|max:100',
            'status'         => 'required|in:Wajib,Pilihan',
            'ipk_min'        => 'nullable|numeric|min:0|max:4',
            'sks_min'        => 'nullable|integer|min:0',
            'grade_min'      => 'nullable|string|max:2',
            'mk_persyaratan' => 'nullable|string|max:20',
            'urutan'         => 'nullable|integer|min:1',
        ]);
        $v['updated_by'] = Session::get('admin_id');
        $k->update($v);
        return redirect()->route('admin.kurikulum.index')->with('success','Kurikulum diperbarui.');
    }

    public function destroy($id)
    {
        Kurikulum::findOrFail($id)->delete();
        return redirect()->route('admin.kurikulum.index')->with('success','Kurikulum dihapus.');
    }
}
