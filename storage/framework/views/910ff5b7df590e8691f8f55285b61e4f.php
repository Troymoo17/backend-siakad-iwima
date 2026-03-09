<?php $__env->startSection('title','Point Book'); ?> <?php $__env->startSection('page-title','Point Book Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="<?php echo e(request('nim')); ?>">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        <?php if(request('nim')): ?><a href="<?php echo e(route('admin.pointbook.index')); ?>" class="btn btn-light">Reset</a><?php endif; ?>
    </div>
    <?php if(request('nim') && $rekapNim !== null): ?>
    <div class="mt-2">
        <div class="alert alert-light py-2 mb-0 d-inline-flex align-items-center gap-2">
            <i class="fas fa-medal text-warning fa-lg"></i>
            <span>Total Poin untuk NIM <strong><?php echo e(request('nim')); ?></strong>: 
            <span class="badge bg-warning text-dark fs-6"><?php echo e($rekapNim); ?> Poin</span></span>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-warning"></i>Tambah Poin Kegiatan</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.pointbook.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <div class="d-flex gap-1">
                            <input type="text" name="nim" id="nimPoint" class="form-control" value="<?php echo e(old('nim', request('nim'))); ?>" required placeholder="22.240.0001">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="cariNIM()"><i class="fas fa-search"></i></button>
                        </div>
                        <div id="nimPointInfo" class="mt-1"></div></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kegiatan" class="form-control" value="<?php echo e(old('nama_kegiatan')); ?>" required placeholder="Lomba, Seminar, dll..."></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Poin <span class="text-danger">*</span></label>
                            <input type="number" name="poin" class="form-control" value="<?php echo e(old('poin',1)); ?>" min="1" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?php echo e(old('keterangan')); ?>" placeholder="Opsional"></div>
                    <button type="submit" class="btn btn-warning w-100"><i class="fas fa-plus me-1"></i>Tambah Poin</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-medal me-2"></i>Data Point Book (<?php echo e($points->total()); ?>)</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>Kegiatan</th><th>Poin</th><th>Tanggal</th><th>Keterangan</th><th>Hapus</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($p->nim); ?></td>
                            <td><small><?php echo e($p->mahasiswa?->nama); ?></small></td>
                            <td><?php echo e($p->nama_kegiatan); ?></td>
                            <td><span class="badge bg-warning text-dark fs-6"><i class="fas fa-star me-1"></i><?php echo e($p->poin); ?></span></td>
                            <td><small><?php echo e(\Carbon\Carbon::parse($p->tanggal)->format('d/m/Y')); ?></small></td>
                            <td><small class="text-muted"><?php echo e($p->keterangan ?? '-'); ?></small></td>
                            <td>
                                <form action="<?php echo e(route('admin.pointbook.destroy',$p->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus poin?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($points->links('pagination::bootstrap-5')); ?>

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
    const nim = document.getElementById('nimPoint').value.trim(); if (!nim) return;
    fetch('<?php echo e(route("admin.pointbook.search-mhs")); ?>?nim='+encodeURIComponent(nim), {
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}
    }).then(r=>r.json()).then(data=>{
        const el=document.getElementById('nimPointInfo');
        if(data.success&&data.data) el.innerHTML='<div class="alert alert-info py-1 small mb-0">'+data.data.nama+' – '+data.data.kelas+'</div>';
        else el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/pointbook/index.blade.php ENDPATH**/ ?>