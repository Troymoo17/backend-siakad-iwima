<?php $__env->startSection('title','Download Materi'); ?> <?php $__env->startSection('page-title','Upload & Download Materi'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header" style="background:linear-gradient(135deg,#1a3a5c,#2d6a9f);color:#fff">
                <i class="fas fa-cloud-upload-alt me-2"></i>Upload File Materi / Dokumen
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.download.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama / Keterangan File <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('keterangan')); ?>" required
                               placeholder="Contoh: Silabus Pemrograman Web Semester 5">
                        <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = ['Silabus','Modul','Materi Kuliah','Formulir','Panduan','Pengumuman','Template','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e(old('kategori')===$k?'selected':''); ?>><?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-upload me-1 text-primary"></i>
                            Pilih File <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png" required>
                        <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>
                            Format: PDF, Word, Excel, PPT, ZIP, Gambar &bull; Maks: <strong>20MB</strong>
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-1"></i>Upload File
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle me-2 text-info"></i>Panduan Upload</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PDF, Word (.doc, .docx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Excel (.xls, .xlsx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PowerPoint (.ppt, .pptx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Arsip (.zip, .rar)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gambar (.jpg, .jpeg, .png)</li>
                    <li class="mt-3 text-muted"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Maksimum 20MB per file</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-folder-open me-2"></i>Daftar File Tersedia (<?php echo e($downloads->total()); ?>)</span>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-5"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama file..." value="<?php echo e(request('search')); ?>"></div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            <?php $__currentLoopData = ['Silabus','Modul','Materi Kuliah','Formulir','Panduan','Pengumuman','Template','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php echo e(request('kategori')===$k?'selected':''); ?>><?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search me-1"></i>Cari</button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Nama / Keterangan</th><th>Kategori</th><th>Tipe File</th><th>Tanggal Upload</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $downloads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $ext = pathinfo($d->file_path, PATHINFO_EXTENSION);
                            $iconMap = ['pdf'=>'fa-file-pdf text-danger','doc'=>'fa-file-word text-primary','docx'=>'fa-file-word text-primary',
                                'xls'=>'fa-file-excel text-success','xlsx'=>'fa-file-excel text-success',
                                'ppt'=>'fa-file-powerpoint text-warning','pptx'=>'fa-file-powerpoint text-warning',
                                'zip'=>'fa-file-archive text-secondary','rar'=>'fa-file-archive text-secondary',
                                'jpg'=>'fa-file-image text-info','jpeg'=>'fa-file-image text-info','png'=>'fa-file-image text-info'];
                            $icon = $iconMap[$ext] ?? 'fa-file text-muted';
                        ?>
                        <tr>
                            <td>
                                <i class="fas <?php echo e($icon); ?> me-2 fa-lg"></i>
                                <strong><?php echo e($d->keterangan); ?></strong>
                            </td>
                            <td>
                                <?php if($d->kategori): ?>
                                <span class="badge bg-info text-dark"><?php echo e($d->kategori); ?></span>
                                <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary text-uppercase"><?php echo e(strtoupper($ext)); ?></span></td>
                            <td>
                                <small class="text-muted">
                                    <?php echo e($d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d M Y H:i') : '-'); ?>

                                </small>
                            </td>
                            <td>
                                <a href="<?php echo e(Storage::disk('public')->url($d->file_path)); ?>" target="_blank"
                                   class="btn btn-xs btn-success btn-sm py-0 px-2" title="Download">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                                <form action="<?php echo e(route('admin.download.destroy',$d->id)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus file ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-2 d-block opacity-25"></i>
                                Belum ada file yang diupload
                            </td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($downloads->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/download/index.blade.php ENDPATH**/ ?>