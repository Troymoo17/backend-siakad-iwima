<?php $__env->startSection('title','Kalender Akademik'); ?> <?php $__env->startSection('page-title','Kalender Akademik'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small">Total: <?php echo e($kalenders->total()); ?> kegiatan</span>
    <a href="<?php echo e(route('admin.kalender.create')); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Kegiatan</a>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-check me-2"></i>Daftar Kegiatan Akademik</span>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><select name="kategori" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = ['akademik','libur','ujian','event','wisuda','lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>" <?php echo e(request('kategori')===$k?'selected':''); ?>><?php echo e(ucfirst($k)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select></div>
            <div class="col-md-2"><select name="is_published" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="1" <?php echo e(request('is_published')==='1'?'selected':''); ?>>Dipublish</option>
                <option value="0" <?php echo e(request('is_published')==='0'?'selected':''); ?>>Draft</option>
            </select></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Judul</th><th>Kategori</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>File</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kalenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($k->judul); ?></strong><br><small class="text-muted"><?php echo e(Str::limit($k->deskripsi, 60)); ?></small></td>
                    <td>
                        <?php $kColors=['akademik'=>'primary','libur'=>'success','ujian'=>'danger','event'=>'info','wisuda'=>'warning','lainnya'=>'secondary']; ?>
                        <span class="badge bg-<?php echo e($kColors[$k->kategori]??'secondary'); ?>"><?php echo e(ucfirst($k->kategori)); ?></span>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($k->tanggal_mulai)->format('d M Y')); ?></td>
                    <td><?php echo e($k->tanggal_selesai ? \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') : '-'); ?></td>
                    <td>
                        <?php if($k->file_path): ?>
                        <a href="<?php echo e(Storage::disk('public')->url($k->file_path)); ?>" target="_blank" class="btn btn-xs btn-outline-success btn-sm py-0 px-2">
                            <i class="fas fa-download me-1"></i><?php echo e($k->file_nama ?? 'File'); ?>

                        </a>
                        <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo e($k->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($k->is_published ? 'Published' : 'Draft'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.kalender.edit',$k->id)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="<?php echo e(route('admin.kalender.destroy',$k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($kalenders->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/kalender/index.blade.php ENDPATH**/ ?>