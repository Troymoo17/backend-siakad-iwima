<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\KrsSetting;
use App\Models\MataKuliah;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('dosenPA');
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nim','like',"%$s%")->orWhere('nama','like',"%$s%")->orWhere('email','like',"%$s%"));
        }
        if ($request->kelas)       $query->where('kelas', $request->kelas);
        if ($request->status_aktif) $query->where('status_aktif', $request->status_aktif);
        if ($request->angkatan)    $query->where('angkatan', $request->angkatan);

        $mahasiswas = $query->orderBy('nim')->paginate(20)->withQueryString();
        $dosenList  = Dosen::where('is_active', 1)->orderBy('nama')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas', 'dosenList'));
    }

    public function show($nim)
    {
        $mahasiswa = Mahasiswa::with(['dosenPA','khs','tagihan','skripsiPengajuan','pointBook'])->findOrFail($nim);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function create()
    {
        $dosenList = Dosen::where('is_active', 1)->orderBy('nama')->get();
        return view('admin.mahasiswa.create', compact('dosenList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'            => 'required|string|unique:mahasiswa,nim',
            'nama'           => 'required|string|max:255',
            'password'       => 'required|string|min:6',
            'prodi'          => 'required|string',
            'program'        => 'required|in:D3,D4,S1,S2,S3',
            'kelas'          => 'required|string',
            'angkatan'       => 'required|integer',
            'email'          => 'nullable|email|unique:mahasiswa,email',
            'jenis_kelamin'  => 'nullable|in:Laki-laki,Perempuan',
            'dosen_pa_id'    => 'nullable|exists:dosen,id',
        ]);
        $validated['password']         = Hash::make($validated['password']);
        $validated['status_aktif']     = 'Aktif';
        $validated['semester_sekarang']= 1;
        Mahasiswa::create($validated);
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit($nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $dosenList = Dosen::where('is_active', 1)->orderBy('nama')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosenList'));
    }

    public function update(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $validated = $request->validate([
            'nama'             => 'required|string|max:255',
            'email'            => 'nullable|email|unique:mahasiswa,email,'.$nim.',nim',
            'kelas'            => 'required|string',
            'semester_sekarang'=> 'required|integer|min:1|max:14',
            'status_aktif'     => 'required|in:Aktif,Cuti,Keluar,Lulus',
            'dosen_pa_id'      => 'nullable|exists:dosen,id',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto) Storage::disk('public')->delete($mahasiswa->foto);
            $file     = $request->file('foto');
            $filename = 'foto_'.$nim.'_'.time().'.'.$file->extension();
            $file->storeAs('foto_mahasiswa', $filename, 'public');
            $mahasiswa->foto = 'foto_mahasiswa/'.$filename;
            $mahasiswa->save();
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        unset($validated['foto']);
        $mahasiswa->update(array_filter($validated, fn($v) => $v !== null));

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy($nim)
    {
        $mhs = Mahasiswa::findOrFail($nim);
        if ($mhs->foto) Storage::disk('public')->delete($mhs->foto);
        $mhs->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }

    // ── AJAX: upload foto profil mahasiswa ──────────────────────
    public function uploadFoto(Request $request, $nim)
    {
        $request->validate(['foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
        $mahasiswa = Mahasiswa::findOrFail($nim);

        if ($mahasiswa->foto) Storage::disk('public')->delete($mahasiswa->foto);
        $file     = $request->file('foto');
        $filename = 'foto_'.$nim.'_'.time().'.'.$file->extension();
        $file->storeAs('foto_mahasiswa', $filename, 'public');
        $mahasiswa->update(['foto' => 'foto_mahasiswa/'.$filename]);

        return response()->json([
            'success'  => true,
            'message'  => 'Foto berhasil diperbarui.',
            'foto_url' => url('storage/foto_mahasiswa/'.$filename),
        ]);
    }

    // ── KRS Setting: buka/tutup KRS per mahasiswa ───────────────
    public function krsSettingIndex(Request $request)
    {
        $query = KrsSetting::with('mahasiswa');
        if ($request->nim) $query->where('nim','like','%'.$request->nim.'%');
        $settings = $query->orderBy('nim')->paginate(25)->withQueryString();
        return view('admin.mahasiswa.krs_setting', compact('settings'));
    }

    public function krsSettingStore(Request $request)
    {
        $v = $request->validate([
            'nim'            => 'required|exists:mahasiswa,nim',
            'semester'       => 'required|integer|min:1|max:14',
            'tahun_akademik' => 'required|string|max:20',
            'is_aktif'       => 'boolean',
            'kode_mk'        => 'nullable|array',
            'kode_mk.*'      => 'exists:mata_kuliah,kode_mk',
        ]);

        $setting = KrsSetting::updateOrCreate(
            ['nim'=>$v['nim'],'semester'=>$v['semester'],'tahun_akademik'=>$v['tahun_akademik']],
            ['is_aktif'=>$request->boolean('is_aktif',true),'created_by'=>Session::get('admin_id')]
        );

        return response()->json(['success'=>true,'message'=>'Setting KRS disimpan.','data'=>$setting]);
    }

    public function krsSettingToggle(Request $request, $id)
    {
        $setting = KrsSetting::findOrFail($id);
        $setting->update(['is_aktif' => !$setting->is_aktif]);
        return response()->json(['success'=>true,'is_aktif'=>$setting->is_aktif]);
    }

    public function krsSettingDestroy($id)
    {
        KrsSetting::findOrFail($id)->delete();
        return redirect()->back()->with('success','Setting KRS dihapus.');
    }

    // AJAX: search mahasiswa by NIM (untuk KRS setting form)
    public function searchMahasiswaKrs(Request $request)
    {
        $mhs = Mahasiswa::where('nim',$request->nim)->first(['nim','nama','kelas','semester_sekarang','prodi']);
        if (!$mhs) return response()->json(['success'=>false,'message'=>'Mahasiswa tidak ditemukan.']);

        // Kurikulum semester berikutnya (default semester+1)
        $nextSem   = $mhs->semester_sekarang + 1;
        $kurikulum = Kurikulum::where('prodi',$mhs->prodi)
            ->where('semester',$request->semester ?? $nextSem)
            ->with('mataKuliah')
            ->get()
            ->map(fn($k)=>[
                'kode_mk' => $k->kode_mk,
                'nama_mk' => $k->mataKuliah->nama_mk ?? $k->kode_mk,
                'sks'     => $k->mataKuliah->sks ?? 0,
                'status'  => $k->status,
            ]);

        return response()->json(['success'=>true,'data'=>$mhs,'kurikulum'=>$kurikulum,'next_semester'=>$nextSem]);
    }
}