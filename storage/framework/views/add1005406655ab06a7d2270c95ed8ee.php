<?php $__env->startSection('title','Edit MK'); ?> <?php $__env->startSection('page-title','Edit Mata Kuliah'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-edit me-2"></i>Edit: <?php echo e($mk->kode_mk); ?></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.matakuliah.update',$mk->kode_mk)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="mb-3"><label class="form-label fw-semibold">Nama MK <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" class="form-control" value="<?php echo e(old('nama_mk',$mk->nama_mk)); ?>" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label fw-semibold">SKS</label>
                            <input type="number" name="sks" class="form-control" value="<?php echo e(old('sks',$mk->sks)); ?>" min="1" max="6"></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e(old('semester',$mk->semester)); ?>" min="1" max="14"></div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Prodi</label>
                        <input type="text" name="prodi" class="form-control" value="<?php echo e(old('prodi',$mk->prodi)); ?>"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo e(old('deskripsi',$mk->deskripsi)); ?></textarea></div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                        <a href="<?php echo e(route('admin.matakuliah.index')); ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-chalkboard-teacher me-2"></i>Assign Dosen Pengampu</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.matakuliah.assign-dosen',$mk->kode_mk)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">Dosen</label>
                        <select name="dosen_id" class="form-select select2" required>
                            <option value="">-- Pilih Dosen --</option>
                            <?php $__currentLoopData = $dosenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>"><?php echo e($d->nama); ?> (<?php echo e($d->nidn); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="2025/2026" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e($mk->semester); ?>" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="IF-2022-A" required></div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-plus me-1"></i>Tambah Pengampu</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i>Dosen Pengampu (<?php echo e($pengampus->count()); ?>)</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Dosen</th><th>Kelas</th><th>Tahun Akademik</th><th>Hapus</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pengampus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($dm->dosen?->nama); ?></td>
                        <td><?php echo e($dm->kelas); ?></td>
                        <td><?php echo e($dm->tahun_akademik); ?></td>
                        <td>
                            <form action="<?php echo e(route('admin.matakuliah.remove-dosen',$dm->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengampu?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengampu</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/matakuliah/edit.blade.php ENDPATH**/ ?>