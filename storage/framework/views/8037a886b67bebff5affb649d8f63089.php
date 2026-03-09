<?php $__env->startSection('title','Perpustakaan'); ?> <?php $__env->startSection('page-title','Manajemen Pinjaman Perpustakaan'); ?>
<?php $__env->startSection('content'); ?>
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="<?php echo e(request('nim')); ?>">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        <?php if(request('nim')): ?><a href="<?php echo e(route('admin.perpustakaan.index')); ?>" class="btn btn-light">Reset</a><?php endif; ?>
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Input Pinjaman Buku</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.perpustakaan.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <div class="d-flex gap-1">
                            <input type="text" name="nim" id="nimPinjam" class="form-control" value="<?php echo e(old('nim', request('nim'))); ?>" required placeholder="22.240.0001">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="cariNIM()"><i class="fas fa-search"></i></button>
                        </div>
                        <div id="nimPinjamInfo" class="mt-1"></div></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" value="<?php echo e(old('kode_buku')); ?>" placeholder="INF-001"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Nama Buku <span class="text-danger">*</span></label>
                        <input type="text" name="nama_buku" class="form-control" value="<?php echo e(old('nama_buku')); ?>" required placeholder="Judul buku..."></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="<?php echo e(old('tanggal_pinjam', date('Y-m-d'))); ?>" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Batas Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control" value="<?php echo e(old('tanggal_kembali', date('Y-m-d', strtotime('+14 days')))); ?>" required></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-book me-1"></i>Pinjamkan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-book-open me-2"></i>Data Pinjaman (<?php echo e($pinjamans->total()); ?>)</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <input type="hidden" name="nim" value="<?php echo e(request('nim')); ?>">
                    <div class="col-md-4"><select name="status_pinjaman" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <?php $__currentLoopData = ['Dipinjam','Sudah Kembali','Terlambat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status_pinjaman')===$s?'selected':''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM / Nama</th><th>Buku</th><th>Pinjam</th><th>Batas Kembali</th><th>Status</th><th>Denda</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pinjamans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($p->status_pinjaman==='Terlambat' ? 'table-danger' : ($p->status_pinjaman==='Dipinjam' && $p->tanggal_kembali < date('Y-m-d') ? 'table-warning' : '')); ?>">
                            <td><strong><?php echo e($p->nim); ?></strong><br><small><?php echo e($p->mahasiswa?->nama); ?></small></td>
                            <td><strong><?php echo e($p->nama_buku); ?></strong><br><small class="text-muted"><?php echo e($p->kode_buku); ?></small></td>
                            <td><small><?php echo e(\Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y')); ?></small></td>
                            <td><small class="<?php echo e($p->tanggal_kembali < date('Y-m-d') && $p->status_pinjaman==='Dipinjam' ? 'text-danger fw-bold' : ''); ?>">
                                <?php echo e(\Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y')); ?>

                            </small></td>
                            <td>
                                <?php $sc=['Dipinjam'=>'primary','Sudah Kembali'=>'success','Terlambat'=>'danger']; ?>
                                <span class="badge bg-<?php echo e($sc[$p->status_pinjaman]??'secondary'); ?>"><?php echo e($p->status_pinjaman); ?></span>
                            </td>
                            <td><?php echo e($p->denda > 0 ? 'Rp '.number_format($p->denda,0,',','.') : '-'); ?></td>
                            <td>
                                <?php if($p->status_pinjaman === 'Dipinjam'): ?>
                                <form action="<?php echo e(route('admin.perpustakaan.kembalikan',$p->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan buku?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-xs btn-success btn-sm py-0 px-2"><i class="fas fa-undo me-1"></i>Kembali</button>
                                </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.perpustakaan.destroy',$p->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data pinjaman</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($pinjamans->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
function filterNIM() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const url = new URL(window.location.href);
    url.searchParams.set('nim', nim);
    window.location.href = url.toString();
}
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();filterNIM();} });
function cariNIM() {
    const nim = document.getElementById('nimPinjam').value.trim(); if (!nim) return;
    fetch('<?php echo e(route("admin.perpustakaan.search-mhs")); ?>?nim='+encodeURIComponent(nim), {
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}
    }).then(r=>r.json()).then(data=>{
        const el=document.getElementById('nimPinjamInfo');
        if(data.success&&data.data) el.innerHTML='<div class="alert alert-info py-1 small mb-0">'+data.data.nama+' – '+data.data.kelas+'</div>';
        else el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/perpustakaan/index.blade.php ENDPATH**/ ?>