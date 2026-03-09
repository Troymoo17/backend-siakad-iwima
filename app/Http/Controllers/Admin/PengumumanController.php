<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    protected NotificationService $notifService;
    public function __construct(NotificationService $notifService) { $this->notifService = $notifService; }

    public function index()
    {
        $pengumuman = Pengumuman::orderBy('created_at','desc')->paginate(15);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'judul'          => 'required|string|max:255',
            'isian'          => 'required|string',
            'tanggal_upload' => 'required|date',
            'is_published'   => 'boolean',
            'file_pengumuman'=> 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);
        $v['created_by']   = Session::get('admin_id');
        $v['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('file_pengumuman')) {
            $file = $request->file('file_pengumuman');
            $filename = time().'_'.str_replace(' ','_',$v['judul']).'.'.$file->extension();
            $file->storeAs('pengumuman', $filename, 'public');
            $v['file_path'] = 'pengumuman/'.$filename;
            $v['file_nama'] = $file->getClientOriginalName();
        }

        $peng = Pengumuman::create($v);

        if ($peng->is_published) {
            $this->notifService->sendToAll(
                'Pengumuman: '.$peng->judul,
                substr($peng->isian, 0, 200),
                'pengumuman', '/pengumuman/'.$peng->id,
                Session::get('admin_id')
            );
        }
        return redirect()->route('admin.pengumuman.index')->with('success','Pengumuman berhasil dibuat.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $p = Pengumuman::findOrFail($id);
        $v = $request->validate([
            'judul'          => 'required|string|max:255',
            'isian'          => 'required|string',
            'tanggal_upload' => 'required|date',
            'file_pengumuman'=> 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);
        $v['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('file_pengumuman')) {
            if ($p->file_path) Storage::disk('public')->delete($p->file_path);
            $file = $request->file('file_pengumuman');
            $filename = time().'_'.$id.'.'.$file->extension();
            $file->storeAs('pengumuman', $filename, 'public');
            $v['file_path'] = 'pengumuman/'.$filename;
            $v['file_nama'] = $file->getClientOriginalName();
        }
        $p->update($v);
        return redirect()->route('admin.pengumuman.index')->with('success','Pengumuman diperbarui.');
    }

    public function destroy($id)
    {
        $p = Pengumuman::findOrFail($id);
        if ($p->file_path) Storage::disk('public')->delete($p->file_path);
        $p->delete();
        return redirect()->route('admin.pengumuman.index')->with('success','Pengumuman dihapus.');
    }
}
