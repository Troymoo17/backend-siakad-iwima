<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = BannerKegiatan::orderBy('urutan')->orderBy('created_at', 'desc')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'   => 'required|string|max:150',
            'gambar'  => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'urutan'  => 'nullable|integer|min:1',
        ]);

        $file     = $request->file('gambar');
        $filename = time() . '_banner_' . Str::slug($request->judul) . '.' . $file->extension();
        $file->storeAs('banner_kegiatan', $filename, 'public');

        BannerKegiatan::create([
            'judul'      => $request->judul,
            'file_path'  => 'banner_kegiatan/' . $filename,
            'file_nama'  => $file->getClientOriginalName(),
            'urutan'     => $request->urutan ?? (BannerKegiatan::max('urutan') + 1),
            'is_aktif'   => true,
            'created_by' => Session::get('admin_id'),
        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function toggleAktif(Request $request, $id)
    {
        $banner = BannerKegiatan::findOrFail($id);
        $banner->update(['is_aktif' => !$banner->is_aktif]);
        return response()->json(['success' => true, 'is_aktif' => $banner->is_aktif]);
    }

    public function updateUrutan(Request $request)
    {
        $request->validate(['urutan' => 'required|array', 'urutan.*' => 'integer']);
        foreach ($request->urutan as $id => $urutan) {
            BannerKegiatan::where('id', $id)->update(['urutan' => $urutan]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $banner = BannerKegiatan::findOrFail($id);
        Storage::disk('public')->delete($banner->file_path);
        $banner->delete();
        return response()->json(['success' => true, 'message' => 'Banner dihapus.']);
    }
}
