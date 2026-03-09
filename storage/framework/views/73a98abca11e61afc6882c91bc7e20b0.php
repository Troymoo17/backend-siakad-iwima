<?php $__env->startSection('title', 'Manajemen Notifikasi'); ?>
<?php $__env->startSection('page-title', 'Manajemen Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bell me-2"></i>Daftar Notifikasi</span>
        <a href="<?php echo e(route('admin.notifikasi.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover small mb-0">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Tipe</th><th>Target</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $notifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($n->judul); ?></td>
                        <td>
                            <span class="badge badge-tipe bg-<?php echo e($n->tipe === 'akademik' ? 'primary' : ($n->tipe === 'keuangan' ? 'warning text-dark' : ($n->tipe === 'nilai' ? 'success' : 'info'))); ?>">
                                <?php echo e($n->tipe); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($n->target); ?></span>
                            <?php if($n->target_value): ?> <small class="text-muted">(<?php echo e($n->target_value); ?>)</small> <?php endif; ?>
                        </td>
                        <td><?php echo e($n->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <form action="<?php echo e(route('admin.notifikasi.destroy', $n->id)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus notifikasi ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada notifikasi</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3"><?php echo e($notifikasi->links('pagination::bootstrap-5')); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/notifikasi/index.blade.php ENDPATH**/ ?>