<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $query = DownloadMateri::query();
        if ($s = $request->search)  $query->where('keterangan','like',"%$s%");
        if ($request->kategori)     $query->where('kategori',$request->kategori);
        $downloads = $query->orderBy('created_at','desc')->paginate(20)->withQueryString();
        return view('admin.download.index', compact('downloads'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'keterangan' => 'required|string|max:255',
            'kategori'   => 'nullable|string|max:100',
            'file'       => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png|max:20480',
        ]);
        $file = $request->file('file');
        $filename = time().'_'.str_replace(' ','_',$v['keterangan']).'.'.$file->extension();
        $path = $file->storeAs('download', $filename, 'public');

        DownloadMateri::create([
            'keterangan' => $v['keterangan'],
            'kategori'   => $v['kategori'],
            'file_path'  => $path,
            'created_by' => Session::get('admin_id'),
        ]);
        return redirect()->route('admin.download.index')->with('success','File berhasil diupload.');
    }

    public function destroy($id)
    {
        $d = DownloadMateri::findOrFail($id);
        Storage::disk('public')->delete($d->file_path);
        $d->delete();
        return redirect()->route('admin.download.index')->with('success','File dihapus.');
    }
}
