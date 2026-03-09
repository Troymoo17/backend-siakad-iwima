<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\GambarKegiatan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class KalenderController extends Controller
{
    protected NotificationService $notif;
    public function __construct(NotificationService $notif) { $this->notif = $notif; }

    public function index(Request $request)
    {
        $query = KalenderAkademik::with('gambar');
        if ($request->kategori)   $query->where('kategori',$request->kategori);
        if ($request->is_published !== null && $request->is_published !== '') $query->where('is_published',$request->is_published);
        $kalenders = $query->orderBy('tanggal_mulai')->paginate(20)->withQueryString();
        return view('admin.kalender.index', compact('kalenders'));
    }

    public function create()
    {
        return view('admin.kalender.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kategori'        => 'required|in:akademik,libur,ujian,event,wisuda,lainnya',
            'is_published'    => 'boolean',
            'file_kalender'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gambar.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
        $v['is_published'] = $request->boolean('is_published', true);
        $v['created_by']   = Session::get('admin_id');

        if ($request->hasFile('file_kalender')) {
            $file = $request->file('file_kalender');
            $filename = time().'_kalender_'.str_replace(' ','_',$v['judul']).'.'.$file->extension();
            $file->storeAs('kalender', $filename, 'public');
            $v['file_path'] = 'kalender/'.$filename;
            $v['file_nama'] = $file->getClientOriginalName();
        }

        $kal = KalenderAkademik::create($v);

        // Upload multi-gambar kegiatan
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $idx => $img) {
                $imgName = time().'_gambar_'.$kal->id.'_'.$idx.'.'.$img->extension();
                $img->storeAs('gambar_kegiatan', $imgName, 'public');
                GambarKegiatan::create([
                    'kalender_id' => $kal->id,
                    'file_path'   => 'gambar_kegiatan/'.$imgName,
                    'file_nama'   => $img->getClientOriginalName(),
                    'urutan'      => $idx + 1,
                ]);
            }
        }

        if ($kal->is_published) {
            $this->notif->sendToAll(
                'Kegiatan Akademik: '.$kal->judul,
                $kal->deskripsi ?? 'Kegiatan akademik baru telah ditambahkan.',
                'akademik', '/kalender', Session::get('admin_id')
            );
        }
        return redirect()->route('admin.kalender.index')->with('success','Kalender ditambahkan.');
    }

    public function edit($id)
    {
        $kalender = KalenderAkademik::with('gambar')->findOrFail($id);
        return view('admin.kalender.edit', compact('kalender'));
    }

    public function update(Request $request, $id)
    {
        $kal = KalenderAkademik::findOrFail($id);
        $v   = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'kategori'        => 'required|in:akademik,libur,ujian,event,wisuda,lainnya',
            'file_kalender'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gambar.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
        $v['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('file_kalender')) {
            if ($kal->file_path) Storage::disk('public')->delete($kal->file_path);
            $file     = $request->file('file_kalender');
            $filename = time().'_kalender_'.$id.'.'.$file->extension();
            $file->storeAs('kalender', $filename, 'public');
            $v['file_path'] = 'kalender/'.$filename;
            $v['file_nama'] = $file->getClientOriginalName();
        }
        $kal->update($v);

        // Upload gambar baru (append, tidak replace)
        if ($request->hasFile('gambar')) {
            $lastUrutan = GambarKegiatan::where('kalender_id',$id)->max('urutan') ?? 0;
            foreach ($request->file('gambar') as $idx => $img) {
                $imgName = time().'_gambar_'.$id.'_'.$idx.'.'.$img->extension();
                $img->storeAs('gambar_kegiatan', $imgName, 'public');
                GambarKegiatan::create([
                    'kalender_id' => $id,
                    'file_path'   => 'gambar_kegiatan/'.$imgName,
                    'file_nama'   => $img->getClientOriginalName(),
                    'urutan'      => $lastUrutan + $idx + 1,
                ]);
            }
        }

        return redirect()->route('admin.kalender.index')->with('success','Kalender diperbarui.');
    }

    public function destroy($id)
    {
        $kal = KalenderAkademik::with('gambar')->findOrFail($id);
        if ($kal->file_path) Storage::disk('public')->delete($kal->file_path);
        foreach ($kal->gambar as $g) Storage::disk('public')->delete($g->file_path);
        $kal->delete(); // cascade deletes gambar_kegiatan via FK
        return redirect()->route('admin.kalender.index')->with('success','Kalender dihapus.');
    }

    // AJAX: hapus satu gambar
    public function destroyGambar($id)
    {
        $g = GambarKegiatan::findOrFail($id);
        Storage::disk('public')->delete($g->file_path);
        $g->delete();
        return response()->json(['success'=>true,'message'=>'Gambar dihapus.']);
    }
}