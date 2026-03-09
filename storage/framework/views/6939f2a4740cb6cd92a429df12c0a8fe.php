<?php $__env->startSection('title','Buat Pengumuman'); ?> <?php $__env->startSection('page-title','Buat Pengumuman'); ?>
<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:760px">
    <div class="card-header"><i class="fas fa-bullhorn me-2"></i>Form Pengumuman Baru</div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.pengumuman.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3"><label class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('judul')); ?>" required placeholder="Masukkan judul pengumuman...">
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="mb-3"><label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="isian" class="form-control <?php $__errorArgs = ['isian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="8" required placeholder="Tulis isi pengumuman di sini..."><?php echo e(old('isian')); ?></textarea>
                <?php $__errorArgs = ['isian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Upload <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_upload" class="form-control" value="<?php echo e(old('tanggal_upload', date('Y-m-d'))); ?>" required></div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold"><i class="fas fa-paperclip me-1 text-primary"></i>Lampiran File <small class="text-muted">(opsional, PDF/Word/Gambar, maks 10MB)</small></label>
                    <input type="file" name="file_pengumuman" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublish" value="1" <?php echo e(old('is_published','1') ? 'checked' : ''); ?>>
                    <label class="form-check-label fw-semibold" for="isPublish">
                        Publish & Kirim Notifikasi ke Semua Mahasiswa
                    </label>
                </div>
                <small class="text-muted">Jika dicentang, semua mahasiswa aktif akan mendapat notifikasi otomatis</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan & Publish</button>
                <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/pengumuman/create.blade.php ENDPATH**/ ?>