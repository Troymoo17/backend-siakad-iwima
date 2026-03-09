<?php $__env->startSection('title','Pengumuman'); ?> <?php $__env->startSection('page-title','Manajemen Pengumuman'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bullhorn me-2"></i>Daftar Pengumuman (<?php echo e($pengumuman->total()); ?>)</span>
        <a href="<?php echo e(route('admin.pengumuman.create')); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Pengumuman</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Judul</th><th>Tanggal</th><th>Lampiran</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <strong><?php echo e($p->judul); ?></strong>
                        <br><small class="text-muted"><?php echo e(Str::limit($p->isian, 80)); ?></small>
                    </td>
                    <td><small><?php echo e(\Carbon\Carbon::parse($p->tanggal_upload)->format('d M Y')); ?></small></td>
                    <td>
                        <?php if($p->file_path): ?>
                        <a href="<?php echo e(Storage::disk('public')->url($p->file_path)); ?>" target="_blank" class="btn btn-xs btn-outline-success btn-sm py-0 px-2">
                            <i class="fas fa-download me-1"></i><?php echo e(Str::limit($p->file_nama ?? 'File', 20)); ?>

                        </a>
                        <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo e($p->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($p->is_published ? 'Published' : 'Draft'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.pengumuman.edit',$p->id)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="<?php echo e(route('admin.pengumuman.destroy',$p->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pengumuman</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($pengumuman->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/pengumuman/index.blade.php ENDPATH**/ ?>