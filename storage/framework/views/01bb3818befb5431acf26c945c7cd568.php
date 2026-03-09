<?php $__env->startSection('title', 'Edit Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Edit Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header"><i class="fas fa-user-edit me-2"></i>Edit Mahasiswa: <?php echo e($mahasiswa->nama); ?></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.mahasiswa.update', $mahasiswa->nim)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIM</label>
                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->nim); ?>" disabled>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nama', $mahasiswa->nama)); ?>" required>
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email', $mahasiswa->email)); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control" value="<?php echo e(old('kelas', $mahasiswa->kelas)); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Semester Sekarang <span class="text-danger">*</span></label>
                    <input type="number" name="semester_sekarang" class="form-control" min="1" max="14"
                           value="<?php echo e(old('semester_sekarang', $mahasiswa->semester_sekarang)); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status Aktif <span class="text-danger">*</span></label>
                    <select name="status_aktif" class="form-select" required>
                        <?php $__currentLoopData = ['Aktif','Cuti','Keluar','Lulus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status_aktif', $mahasiswa->status_aktif) == $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dosen PA</label>
                    <select name="dosen_pa_id" class="form-select">
                        <option value="">-- Pilih Dosen PA --</option>
                        <?php $__currentLoopData = $dosenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>" <?php echo e(old('dosen_pa_id', $mahasiswa->dosen_pa_id) == $d->id ? 'selected' : ''); ?>>
                            <?php echo e($d->nama); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="<?php echo e(route('admin.mahasiswa.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/mahasiswa/edit.blade.php ENDPATH**/ ?>