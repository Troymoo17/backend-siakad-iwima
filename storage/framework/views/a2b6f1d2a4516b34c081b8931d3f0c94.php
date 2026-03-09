<?php $__env->startSection('title','Data Dosen'); ?> <?php $__env->startSection('page-title','Data Dosen'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Daftar Dosen</span>
        <a href="<?php echo e(route('admin.dosen.create')); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Dosen</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari NIDN / Nama / Email..." value="<?php echo e(request('search')); ?>"></div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['Tetap','Tidak Tetap','Luar Biasa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status')==$s?'selected':''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="1" <?php echo e(request('is_active')==='1'?'selected':''); ?>>Aktif</option>
                    <option value="0" <?php echo e(request('is_active')==='0'?'selected':''); ?>>Non-aktif</option>
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-2"><a href="<?php echo e(route('admin.dosen.index')); ?>" class="btn btn-outline-secondary btn-sm w-100">Reset</a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>NIDN</th><th>Nama</th><th>Jabatan</th><th>Status</th><th>Email</th><th>Bimbingan</th><th>Aktif</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($d->nidn); ?></td>
                    <td>
                        <?php echo e($d->gelar_depan); ?> <strong><?php echo e($d->nama); ?></strong><?php echo e($d->gelar_belakang ? ', '.$d->gelar_belakang : ''); ?>

                        <br><small class="text-muted"><?php echo e($d->prodi); ?></small>
                    </td>
                    <td><?php echo e($d->jabatan_akademik); ?></td>
                    <td><span class="badge <?php echo e($d->status==='Tetap'?'bg-success':'bg-secondary'); ?>"><?php echo e($d->status); ?></span></td>
                    <td><small><?php echo e($d->email); ?></small></td>
                    <td><span class="badge bg-info"><?php echo e($d->mahasiswa_bimbingan_count); ?> mhs</span></td>
                    <td><span class="badge <?php echo e($d->is_active?'bg-success':'bg-danger'); ?>"><?php echo e($d->is_active?'Aktif':'Non-aktif'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.dosen.show',$d->id)); ?>" class="btn btn-xs btn-outline-info btn-sm py-0 px-2"><i class="fas fa-eye"></i></a>
                        <a href="<?php echo e(route('admin.dosen.edit',$d->id)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="<?php echo e(route('admin.dosen.destroy',$d->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus dosen ini?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">Total: <?php echo e($dosens->total()); ?> dosen</small>
            <?php echo e($dosens->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/dosen/index.blade.php ENDPATH**/ ?>