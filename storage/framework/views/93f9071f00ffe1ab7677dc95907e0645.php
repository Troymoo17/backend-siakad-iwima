<?php $__env->startSection('title', 'Input Nilai'); ?>
<?php $__env->startSection('page-title', 'Input Nilai Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2"></i>Input / Update Nilai</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.nilai.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" class="form-control" value="<?php echo e(old('nim')); ?>" required placeholder="22.240.0007">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk" class="form-select" required>
                            <option value="">-- Pilih MK --</option>
                            <?php $__currentLoopData = $mataKuliahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(old('kode_mk') == $mk->kode_mk ? 'selected' : ''); ?>>
                                <?php echo e($mk->kode_mk); ?> - <?php echo e($mk->nama_mk); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e(old('semester')); ?>" min="1" max="14" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="<?php echo e(old('tahun_akademik','2025/2026')); ?>" placeholder="2025/2026" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-semibold">Tugas (30%)</label>
                            <input type="number" name="nilai_tugas" class="form-control" value="<?php echo e(old('nilai_tugas')); ?>" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">UTS (30%)</label>
                            <input type="number" name="nilai_uts" class="form-control" value="<?php echo e(old('nilai_uts')); ?>" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">UAS (40%)</label>
                            <input type="number" name="nilai_uas" class="form-control" value="<?php echo e(old('nilai_uas')); ?>" min="0" max="100" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dosen Pengampu</label>
                        <input type="number" name="dosen_id" class="form-control" value="<?php echo e(old('dosen_id')); ?>" placeholder="ID Dosen (opsional)">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Simpan & Kirim Notifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>Data Nilai</span>
            </div>
            <div class="card-body p-0">
                <!-- Filter -->
                <div class="p-3 border-bottom">
                    <form method="GET" class="row g-2">
                        <div class="col-4">
                            <input type="text" name="nim" class="form-control form-control-sm" placeholder="NIM..." value="<?php echo e(request('nim')); ?>">
                        </div>
                        <div class="col-4">
                            <select name="kode_mk" class="form-select form-select-sm">
                                <option value="">Semua MK</option>
                                <?php $__currentLoopData = $mataKuliahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(request('kode_mk') == $mk->kode_mk ? 'selected' : ''); ?>><?php echo e($mk->kode_mk); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="number" name="semester" class="form-control form-control-sm" placeholder="Sem" value="<?php echo e(request('semester')); ?>">
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-secondary btn-sm w-100">Cari</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>NIM</th><th>Nama</th><th>MK</th><th>Sem</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Akhir</th><th>Grade</th></tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $nilaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($n->nim); ?></td>
                                <td><?php echo e($n->mahasiswa?->nama); ?></td>
                                <td><?php echo e($n->kode_mk); ?></td>
                                <td><?php echo e($n->semester); ?></td>
                                <td><?php echo e($n->nilai_tugas ?? '-'); ?></td>
                                <td><?php echo e($n->nilai_uts ?? '-'); ?></td>
                                <td><?php echo e($n->nilai_uas ?? '-'); ?></td>
                                <td class="fw-bold"><?php echo e($n->nilai_akhir); ?></td>
                                <td>
                                    <span class="badge <?php echo e(in_array($n->grade, ['A','A-']) ? 'bg-success' : (in_array($n->grade,['B+','B','B-']) ? 'bg-primary' : (in_array($n->grade,['C+','C']) ? 'bg-warning text-dark' : 'bg-danger'))); ?>">
                                        <?php echo e($n->grade); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="9" class="text-center text-muted py-3">Tidak ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3"><?php echo e($nilaiList->links('pagination::bootstrap-5')); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/nilai/index.blade.php ENDPATH**/ ?>