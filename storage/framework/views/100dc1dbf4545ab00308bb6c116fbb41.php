<?php $__env->startSection('title','Edit Dosen'); ?> <?php $__env->startSection('page-title','Edit Dosen'); ?>
<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:720px">
    <div class="card-header"><i class="fas fa-user-edit me-2"></i>Edit: <?php echo e($dosen->nama); ?></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.dosen.update',$dosen->id)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label fw-semibold">NIDN</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($dosen->nidn); ?>" disabled></div>
                <div class="col-md-4"><label class="form-label fw-semibold">NPPY</label>
                    <input type="text" name="nppy" class="form-control" value="<?php echo e(old('nppy',$dosen->nppy)); ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" minlength="6"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="<?php echo e(old('gelar_depan',$dosen->gelar_depan)); ?>"></div>
                <div class="col-md-5"><label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama',$dosen->nama)); ?>" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="<?php echo e(old('gelar_belakang',$dosen->gelar_belakang)); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="<?php echo e(old('prodi',$dosen->prodi)); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Jabatan Akademik</label>
                    <select name="jabatan_akademik" class="form-select">
                        <option value="">-- Pilih --</option>
                        <?php $__currentLoopData = ['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j); ?>" <?php echo e(old('jabatan_akademik',$dosen->jabatan_akademik)===$j?'selected':''); ?>><?php echo e($j); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <?php $__currentLoopData = ['Tetap','Tidak Tetap','Luar Biasa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status',$dosen->status)===$s?'selected':''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email',$dosen->email)); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telp" class="form-control" value="<?php echo e(old('telp',$dosen->telp)); ?>"></div>
                <div class="col-md-4 d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" <?php echo e($dosen->is_active?'checked':''); ?>>
                        <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="<?php echo e(route('admin.dosen.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/dosen/edit.blade.php ENDPATH**/ ?>