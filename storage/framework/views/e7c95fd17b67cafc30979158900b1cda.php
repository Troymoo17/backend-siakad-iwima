<?php $__env->startSection('title', 'Detail Pengaduan'); ?>
<?php $__env->startSection('page-title', 'Detail Pengaduan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-envelope-open me-2"></i>Isi Surat Pengaduan</div>
            <div class="card-body">
                <table class="table table-sm mb-3">
                    <tr><td class="text-muted" width="140">Dari</td><td><strong><?php echo e($surat->nim); ?></strong> - <?php echo e($surat->mahasiswa?->nama); ?></td></tr>
                    <tr><td class="text-muted">Tujuan</td><td><span class="badge bg-secondary"><?php echo e($surat->tujuan); ?></span>
                        <?php if($surat->dosen): ?> <span class="ms-2"><?php echo e($surat->dosen->nama); ?></span> <?php endif; ?>
                    </td></tr>
                    <tr><td class="text-muted">Perihal</td><td><strong><?php echo e($surat->perihal); ?></strong></td></tr>
                    <tr><td class="text-muted">Tanggal</td><td><?php echo e($surat->created_at->format('d M Y H:i')); ?></td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        <span class="badge <?php echo e($surat->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark'); ?>"><?php echo e($surat->status); ?></span>
                    </td></tr>
                </table>
                <div class="bg-light p-3 rounded">
                    <p class="mb-0" style="white-space:pre-wrap"><?php echo e($surat->isi_surat); ?></p>
                </div>
            </div>
        </div>

        <?php if($surat->balasan): ?>
        <div class="card mt-3">
            <div class="card-header bg-success text-white"><i class="fas fa-reply me-2"></i>Balasan</div>
            <div class="card-body">
                <div class="bg-light p-3 rounded mb-2">
                    <p class="mb-0" style="white-space:pre-wrap"><?php echo e($surat->balasan); ?></p>
                </div>
                <small class="text-muted">Dibalas oleh <?php echo e($surat->balasan_oleh); ?> pada <?php echo e($surat->balasan_at?->format('d M Y H:i')); ?></small>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <?php if($surat->status !== 'Selesai'): ?>
        <div class="card">
            <div class="card-header"><i class="fas fa-reply me-2"></i>Balas Pengaduan</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.pengaduan.balas', $surat->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Balasan</label>
                        <textarea name="balasan" class="form-control" rows="6" required placeholder="Tulis balasan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Balasan & Notifikasi
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
        <div class="mt-3">
            <a href="<?php echo e(route('admin.pengaduan.index')); ?>" class="btn btn-secondary w-100">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/pengaduan/show.blade.php ENDPATH**/ ?>