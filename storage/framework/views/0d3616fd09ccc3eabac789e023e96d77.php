<?php $__env->startSection('title','Kurikulum'); ?> <?php $__env->startSection('page-title','Manajemen Kurikulum'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2"></i>Tambah / Update Kurikulum</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.kurikulum.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            <?php $__currentLoopData = $mkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(old('kode_mk')===$mk->kode_mk?'selected':''); ?>><?php echo e($mk->kode_mk); ?> - <?php echo e($mk->nama_mk); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?php echo e(old('semester')); ?>" min="1" max="14" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Prodi</label>
                            <input type="text" name="prodi" class="form-control" value="<?php echo e(old('prodi','Informatika')); ?>" required></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Wajib" <?php echo e(old('status')==='Wajib'?'selected':''); ?>>Wajib</option>
                                <option value="Pilihan" <?php echo e(old('status')==='Pilihan'?'selected':''); ?>>Pilihan</option>
                            </select></div>
                        <div class="col-6"><label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="<?php echo e(old('urutan',1)); ?>" min="1"></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-4"><label class="form-label fw-semibold">IPK Min</label>
                            <input type="number" name="ipk_min" class="form-control" value="<?php echo e(old('ipk_min',0)); ?>" step="0.01" min="0" max="4"></div>
                        <div class="col-4"><label class="form-label fw-semibold">SKS Min</label>
                            <input type="number" name="sks_min" class="form-control" value="<?php echo e(old('sks_min',0)); ?>" min="0"></div>
                        <div class="col-4"><label class="form-label fw-semibold">Grade Min</label>
                            <input type="text" name="grade_min" class="form-control" value="<?php echo e(old('grade_min','D')); ?>" maxlength="2"></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">MK Prasyarat (Kode)</label>
                        <input type="text" name="mk_persyaratan" class="form-control" value="<?php echo e(old('mk_persyaratan')); ?>" placeholder="INF101"></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-sitemap me-2"></i>Daftar Kurikulum (<?php echo e($kurikulums->total()); ?>)</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-3"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Semester" value="<?php echo e(request('semester')); ?>"></div>
                    <div class="col-3"><input type="text" name="prodi" class="form-control form-control-sm" placeholder="Prodi" value="<?php echo e(request('prodi')); ?>"></div>
                    <div class="col-3"><select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Wajib" <?php echo e(request('status')==='Wajib'?'selected':''); ?>>Wajib</option>
                        <option value="Pilihan" <?php echo e(request('status')==='Pilihan'?'selected':''); ?>>Pilihan</option>
                    </select></div>
                    <div class="col-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>#</th><th>MK</th><th>Sem</th><th>Status</th><th>IPK Min</th><th>SKS Min</th><th>Prasyarat</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $kurikulums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($k->urutan); ?></td>
                            <td><strong><?php echo e($k->kode_mk); ?></strong><br><small class="text-muted"><?php echo e($k->mataKuliah?->nama_mk); ?></small></td>
                            <td><?php echo e($k->semester); ?></td>
                            <td><span class="badge <?php echo e($k->status==='Wajib'?'bg-primary':'bg-secondary'); ?>"><?php echo e($k->status); ?></span></td>
                            <td><?php echo e($k->ipk_min > 0 ? number_format($k->ipk_min,2) : '-'); ?></td>
                            <td><?php echo e($k->sks_min > 0 ? $k->sks_min : '-'); ?></td>
                            <td><?php echo e($k->mk_persyaratan ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.kurikulum.edit',$k->id)); ?>" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.kurikulum.destroy',$k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($kurikulums->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/kurikulum/index.blade.php ENDPATH**/ ?>