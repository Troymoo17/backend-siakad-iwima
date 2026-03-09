<?php $__env->startSection('title','Mata Kuliah'); ?> <?php $__env->startSection('page-title','Mata Kuliah'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2"></i>Tambah Mata Kuliah</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.matakuliah.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3"><label class="form-label fw-semibold">Kode MK <span class="text-danger">*</span></label>
                        <input type="text" name="kode_mk" class="form-control <?php $__errorArgs = ['kode_mk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('kode_mk')); ?>" placeholder="INF701" required>
                        <?php $__errorArgs = ['kode_mk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Nama MK <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" class="form-control" value="<?php echo e(old('nama_mk')); ?>" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label fw-semibold">SKS <span class="text-danger">*</span></label>
                            <input type="number" name="sks" class="form-control" value="<?php echo e(old('sks',3)); ?>" min="1" max="6" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e(old('semester')); ?>" min="1" max="14" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Prodi</label>
                        <input type="text" name="prodi" class="form-control" value="<?php echo e(old('prodi','Informatika')); ?>"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo e(old('deskripsi')); ?></textarea></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-book me-2"></i>Daftar Mata Kuliah (<?php echo e($matakuliahs->total()); ?>)</span>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-6"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode / nama MK..." value="<?php echo e(request('search')); ?>"></div>
                    <div class="col-md-3"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Semester..." value="<?php echo e(request('semester')); ?>" min="1" max="14"></div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search me-1"></i>Cari</button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Sem</th><th>Prodi</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $matakuliahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold text-primary"><?php echo e($mk->kode_mk); ?></td>
                            <td><?php echo e($mk->nama_mk); ?></td>
                            <td><span class="badge bg-info"><?php echo e($mk->sks); ?> SKS</span></td>
                            <td><span class="badge bg-secondary">Sem <?php echo e($mk->semester); ?></span></td>
                            <td><small><?php echo e($mk->prodi); ?></small></td>
                            <td>
                                <a href="<?php echo e(route('admin.matakuliah.edit',$mk->kode_mk)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.matakuliah.destroy',$mk->kode_mk)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($matakuliahs->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/matakuliah/index.blade.php ENDPATH**/ ?>