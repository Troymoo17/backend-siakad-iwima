<?php $__env->startSection('title', 'Kirim Notifikasi'); ?>
<?php $__env->startSection('page-title', 'Kirim Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header"><i class="fas fa-paper-plane me-2"></i>Form Kirim Notifikasi</div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.notifikasi.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Notifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" value="<?php echo e(old('judul')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-select" required>
                        <?php $__currentLoopData = ['info','warning','success','error','pengumuman','akademik','keuangan','krs','nilai','skripsi','umum']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('tipe') === $t ? 'selected' : ''); ?>><?php echo e(ucfirst($t)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                    <textarea name="pesan" class="form-control" rows="4" required><?php echo e(old('pesan')); ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Target Penerima <span class="text-danger">*</span></label>
                    <select name="target" id="target" class="form-select" required onchange="toggleTarget(this.value)">
                        <option value="all" <?php echo e(old('target') === 'all' ? 'selected' : ''); ?>>Semua Mahasiswa</option>
                        <option value="prodi" <?php echo e(old('target') === 'prodi' ? 'selected' : ''); ?>>Per Prodi</option>
                        <option value="kelas" <?php echo e(old('target') === 'kelas' ? 'selected' : ''); ?>>Per Kelas</option>
                        <option value="personal" <?php echo e(old('target') === 'personal' ? 'selected' : ''); ?>>Personal (NIM)</option>
                    </select>
                </div>
                <div class="col-md-4" id="target_value_group" style="display:none">
                    <label class="form-label fw-semibold" id="target_value_label">Nilai Target</label>
                    <input type="text" name="target_value" id="target_value" class="form-control" value="<?php echo e(old('target_value')); ?>">
                </div>
                <div class="col-md-4" id="nim_personal_group" style="display:none">
                    <label class="form-label fw-semibold">NIM Mahasiswa</label>
                    <input type="text" name="nim_personal" class="form-control" value="<?php echo e(old('nim_personal')); ?>" placeholder="22.240.0007">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Link (opsional)</label>
                    <input type="text" name="link" class="form-control" value="<?php echo e(old('link')); ?>" placeholder="/mahasiswa/krs">
                    <small class="text-muted">Path halaman yang akan dibuka saat notifikasi diklik</small>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
                </button>
                <a href="<?php echo e(route('admin.notifikasi.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleTarget(val) {
    document.getElementById('target_value_group').style.display = (val === 'prodi' || val === 'kelas') ? '' : 'none';
    document.getElementById('nim_personal_group').style.display = val === 'personal' ? '' : 'none';
    if (val === 'prodi') document.getElementById('target_value_label').textContent = 'Nama Prodi';
    if (val === 'kelas') document.getElementById('target_value_label').textContent = 'Kelas (e.g. IF-2022-A)';
}
toggleTarget('<?php echo e(old("target", "all")); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/notifikasi/create.blade.php ENDPATH**/ ?>