<?php $__env->startSection('title', 'Pengaduan Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Pengaduan Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header"><i class="fas fa-envelope-open-text me-2"></i>Daftar Pengaduan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover small mb-0">
                <thead class="table-light">
                    <tr><th>Mahasiswa</th><th>Perihal</th><th>Tujuan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pengaduan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($s->nim); ?></strong><br><small><?php echo e($s->mahasiswa?->nama); ?></small></td>
                        <td><?php echo e(Str::limit($s->perihal, 50)); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($s->tujuan); ?></span></td>
                        <td>
                            <span class="badge <?php echo e($s->status === 'Selesai' ? 'bg-success' : ($s->status === 'Terkirim' ? 'bg-warning text-dark' : 'bg-info')); ?>">
                                <?php echo e($s->status); ?>

                            </span>
                        </td>
                        <td><?php echo e($s->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.pengaduan.show', $s->id)); ?>" class="btn btn-sm btn-outline-primary py-0 px-2">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada pengaduan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3"><?php echo e($pengaduan->links('pagination::bootstrap-5')); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/pengaduan/index.blade.php ENDPATH**/ ?>