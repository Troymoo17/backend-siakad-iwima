<?php $__env->startSection('title','Detail Dosen'); ?> <?php $__env->startSection('page-title','Detail Dosen'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px">
                    <i class="fas fa-chalkboard-teacher text-white fa-2x"></i>
                </div>
                <h5 class="fw-bold"><?php echo e($dosen->gelar_depan); ?> <?php echo e($dosen->nama); ?><?php echo e($dosen->gelar_belakang ? ', '.$dosen->gelar_belakang : ''); ?></h5>
                <p class="text-muted mb-1"><?php echo e($dosen->nidn); ?></p>
                <span class="badge <?php echo e($dosen->is_active?'bg-success':'bg-danger'); ?>"><?php echo e($dosen->is_active?'Aktif':'Non-aktif'); ?></span>
                <table class="table table-sm text-start mt-3">
                    <tr><td class="text-muted">Prodi</td><td><?php echo e($dosen->prodi); ?></td></tr>
                    <tr><td class="text-muted">Jabatan</td><td><?php echo e($dosen->jabatan_akademik); ?></td></tr>
                    <tr><td class="text-muted">Status</td><td><?php echo e($dosen->status); ?></td></tr>
                    <tr><td class="text-muted">Email</td><td><?php echo e($dosen->email ?? '-'); ?></td></tr>
                    <tr><td class="text-muted">Telepon</td><td><?php echo e($dosen->telp ?? '-'); ?></td></tr>
                </table>
                <a href="<?php echo e(route('admin.dosen.edit',$dosen->id)); ?>" class="btn btn-warning btn-sm w-100"><i class="fas fa-edit me-1"></i>Edit</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-users me-2"></i>Mahasiswa Bimbingan (<?php echo e($dosen->mahasiswaBimbingan->count()); ?>)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>Kelas</th><th>Semester</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dosen->mahasiswaBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr><td><?php echo e($m->nim); ?></td><td><?php echo e($m->nama); ?></td><td><?php echo e($m->kelas); ?></td>
                            <td><?php echo e($m->semester_sekarang); ?></td>
                            <td><span class="badge <?php echo e($m->status_aktif==='Aktif'?'bg-success':'bg-secondary'); ?>"><?php echo e($m->status_aktif); ?></span></td></tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-muted py-2">Belum ada mahasiswa bimbingan</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-book me-2"></i>Mata Kuliah Diampu</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Kode MK</th><th>Nama MK</th><th>Kelas</th><th>Tahun Akademik</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dosen->mataKuliah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr><td><?php echo e($dm->kode_mk); ?></td><td><?php echo e($dm->mataKuliah?->nama_mk); ?></td><td><?php echo e($dm->kelas); ?></td><td><?php echo e($dm->tahun_akademik); ?></td></tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-2">Belum ada MK</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/dosen/show.blade.php ENDPATH**/ ?>