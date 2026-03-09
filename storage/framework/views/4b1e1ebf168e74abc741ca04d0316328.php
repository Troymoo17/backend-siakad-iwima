<?php $__env->startSection('title','Jadwal Kuliah'); ?> <?php $__env->startSection('page-title','Jadwal Kuliah'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Tambah Jadwal Kuliah</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.jadwal.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            <?php $__currentLoopData = $mkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(old('kode_mk')===$mk->kode_mk?'selected':''); ?>><?php echo e($mk->kode_mk); ?> - <?php echo e($mk->nama_mk); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Dosen Pengampu</label>
                        <select name="dosen_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            <?php $__currentLoopData = $dosenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>" <?php echo e(old('dosen_id')==$d->id?'selected':''); ?>><?php echo e($d->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kelas" class="form-control" value="<?php echo e(old('kelas')); ?>" placeholder="IF-2022-A" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="ruang" class="form-control" value="<?php echo e(old('ruang')); ?>" placeholder="R.101" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                        <select name="hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($h); ?>" <?php echo e(old('hari')===$h?'selected':''); ?>><?php echo e($h); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" value="<?php echo e(old('jam_mulai')); ?>" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" value="<?php echo e(old('jam_selesai')); ?>" required></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-5"><label class="form-label fw-semibold">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Teori" <?php echo e(old('jenis')==='Teori'?'selected':''); ?>>Teori</option>
                                <option value="Praktikum" <?php echo e(old('jenis')==='Praktikum'?'selected':''); ?>>Praktikum</option>
                            </select></div>
                        <div class="col-3"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e(old('semester',1)); ?>" min="1" max="14" required></div>
                        <div class="col-4"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="<?php echo e(old('tahun_akademik','2025/2026')); ?>" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Google Classroom ID <small class="text-muted">(opsional)</small></label>
                        <input type="text" name="google_classroom_id" class="form-control" value="<?php echo e(old('google_classroom_id')); ?>" placeholder="classroom.google.com/..."></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan Jadwal</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-alt me-2"></i>Daftar Jadwal Kuliah (<?php echo e($jadwals->total()); ?>)</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari MK / Kelas..." value="<?php echo e(request('search')); ?>"></div>
                    <div class="col-md-2">
                        <select name="hari" class="form-select form-select-sm">
                            <option value="">Semua Hari</option>
                            <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($h); ?>" <?php echo e(request('hari')===$h?'selected':''); ?>><?php echo e($h); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="kelas" class="form-control form-control-sm" placeholder="Kelas" value="<?php echo e(request('kelas')); ?>"></div>
                    <div class="col-md-2"><input type="text" name="tahun_akademik" class="form-control form-control-sm" placeholder="2025/2026" value="<?php echo e(request('tahun_akademik')); ?>"></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>Hari</th><th>MK / Kelas</th><th>Waktu</th><th>Ruang</th><th>Dosen</th><th>Jenis</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo e($j->hari); ?></span>
                            </td>
                            <td>
                                <strong><?php echo e($j->kode_mk); ?></strong><br>
                                <small class="text-muted"><?php echo e($j->nama_mk); ?></small><br>
                                <span class="badge bg-secondary"><?php echo e($j->kelas); ?></span>
                            </td>
                            <td><small><?php echo e(substr($j->jam_mulai,0,5)); ?> – <?php echo e(substr($j->jam_selesai,0,5)); ?></small></td>
                            <td><?php echo e($j->ruang); ?></td>
                            <td><small><?php echo e($j->dosen?->nama ?? '-'); ?></small></td>
                            <td><span class="badge <?php echo e($j->jenis==='Praktikum'?'bg-warning text-dark':'bg-info'); ?>"><?php echo e($j->jenis); ?></span></td>
                            <td>
                                <a href="<?php echo e(route('admin.jadwal.edit',$j->id)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.jadwal.destroy',$j->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada jadwal kuliah</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($jadwals->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/jadwal/index.blade.php ENDPATH**/ ?>