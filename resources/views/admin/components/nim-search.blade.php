{{-- Penggunaan: @include('admin.components.nim-search', ['searchUrl' => route('admin.krs.search-mhs')]) --}}
<div class="nim-search-box">
    <div class="d-flex gap-2 align-items-end">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM lalu klik Cari...">
        </div>
        <button type="button" class="btn btn-warning" onclick="doNimSearch('{{ $searchUrl ?? '' }}')">
            <i class="fas fa-search me-1"></i>Cari
        </button>
    </div>
    <div id="nimSearchResult" class="mt-2"></div>
</div>
@push('scripts')
<script>
function doNimSearch(url) {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    fetch(url + '?nim=' + encodeURIComponent(nim), {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
    }).then(r => r.json()).then(data => {
        const el = document.getElementById('nimSearchResult');
        if (data.success && data.data) {
            const m = data.data;
            el.innerHTML = `<div class="alert alert-light py-2 mb-0 d-flex align-items-center gap-3">
                <i class="fas fa-user-graduate text-primary fa-lg"></i>
                <div><strong>${m.nama}</strong> <span class="text-muted">(${m.nim})</span><br>
                <small class="text-muted">${m.kelas} &bull; Semester ${m.semester_sekarang} &bull; ${m.prodi ?? ''}</small></div>
                <button type="button" class="btn btn-sm btn-primary ms-auto" onclick="fillNIM('${m.nim}','${m.nama}','${m.kelas}','${m.semester_sekarang}')">
                    <i class="fas fa-plus me-1"></i>Pilih
                </button></div>`;
        } else {
            el.innerHTML = `<div class="alert alert-warning py-2 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Mahasiswa dengan NIM "<strong>${nim}</strong>" tidak ditemukan.</div>`;
        }
    }).catch(() => {});
}
document.getElementById('nimSearchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); doNimSearch('{{ $searchUrl ?? '' }}'); }
});
</script>
@endpush
