<?php $__env->startSection('title', 'Data Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Data Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-2"></i>Daftar Mahasiswa</span>
        <a href="<?php echo e(route('admin.mahasiswa.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari NIM / Nama / Email..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="kelas" class="form-control form-control-sm" placeholder="Kelas..." value="<?php echo e(request('kelas')); ?>">
            </div>
            <div class="col-md-2">
                <select name="status_aktif" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['Aktif','Cuti','Keluar','Lulus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status_aktif') == $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('admin.mahasiswa.index')); ?>" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover small">
                <thead class="table-light">
                    <tr>
                        <th>NIM</th><th>Nama</th><th>Kelas</th><th>Semester</th>
                        <th>Dosen PA</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($m->nim); ?></td>
                        <td><?php echo e($m->nama); ?></td>
                        <td><?php echo e($m->kelas); ?></td>
                        <td><span class="badge bg-info">Sem <?php echo e($m->semester_sekarang); ?></span></td>
                        <td><?php echo e($m->dosenPA?->nama ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo e($m->status_aktif === 'Aktif' ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                <?php echo e($m->status_aktif); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.mahasiswa.show', $m->nim)); ?>" class="btn btn-xs btn-outline-info btn-sm py-0 px-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.mahasiswa.edit', $m->nim)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.mahasiswa.destroy', $m->nim)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus mahasiswa <?php echo e($m->nama); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-3 text-muted">Data tidak ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan <?php echo e($mahasiswas->firstItem()); ?>-<?php echo e($mahasiswas->lastItem()); ?> dari <?php echo e($mahasiswas->total()); ?> data</small>
            <?php echo e($mahasiswas->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/mahasiswa/index.blade.php ENDPATH**/ ?>