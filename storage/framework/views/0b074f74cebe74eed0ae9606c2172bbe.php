
<?php $__env->startSection('title','Banner Kegiatan'); ?> <?php $__env->startSection('page-title','Banner Kegiatan Akademik'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.banner-card {
    border-radius: 12px; overflow: hidden;
    border: 1px solid #dee2e6;
    transition: box-shadow .2s, transform .2s;
    background: #fff;
}
.banner-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.10); transform: translateY(-2px); }
.banner-img { width:100%; height:160px; object-fit:cover; display:block; }
.banner-img-placeholder {
    width:100%; height:160px; background:linear-gradient(135deg,#1a3a5c,#2d6a9f);
    display:flex; align-items:center; justify-content:center;
    font-size:2.5rem; color:rgba(255,255,255,.3);
}
.banner-body { padding: 10px 12px; }
.drag-handle { cursor: grab; color: #adb5bd; }
.drag-handle:active { cursor: grabbing; }
.sortable-ghost { opacity: .4; background: #e9f0ff; border: 2px dashed #0d6efd; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-plus-circle text-success"></i>
        <span>Tambah Banner Kegiatan</span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.banner.store')); ?>" enctype="multipart/form-data" id="uploadForm">
            <?php echo csrf_field(); ?>
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('judul')); ?>" required placeholder="Contoh: Wisuda Angkatan 2025">
                    <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Gambar <span class="text-danger">*</span>
                        <small class="text-muted fw-normal">(JPG/PNG/WEBP, maks 4MB)</small>
                    </label>
                    <input type="file" name="gambar" id="gambarInput"
                           class="form-control <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           required onchange="previewGambar(this)">
                    <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="urutan" class="form-control"
                           value="<?php echo e(old('urutan', $banners->count() + 1)); ?>" min="1">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100" id="submitBtn">
                        <i class="fas fa-upload me-1"></i>Upload
                    </button>
                </div>
            </div>

            
            <div id="previewArea" class="mt-3 d-none">
                <p class="small fw-semibold text-muted mb-1">Preview:</p>
                <img id="previewImg" src="" alt="preview"
                     class="rounded" style="max-height:120px; max-width:300px; object-fit:cover; border:2px solid #dee2e6;">
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-images me-2"></i>Daftar Banner (<?php echo e($banners->count()); ?> gambar)</span>
        <small class="text-muted"><i class="fas fa-grip-vertical me-1"></i>Drag untuk ubah urutan</small>
    </div>
    <div class="card-body">
        <?php if($banners->count() === 0): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                <p>Belum ada banner. Upload gambar pertama di atas.</p>
            </div>
        <?php else: ?>
        <div class="row g-3" id="bannerGrid">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3 col-sm-4 col-6 banner-col" data-id="<?php echo e($banner->id); ?>">
                <div class="banner-card">
                    
                    <img src="<?php echo e(Storage::disk('public')->url($banner->file_path)); ?>"
                         alt="<?php echo e($banner->judul); ?>" class="banner-img"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="banner-img-placeholder" style="display:none">🖼️</div>

                    <div class="banner-body">
                        
                        <div class="fw-semibold small text-truncate mb-2" title="<?php echo e($banner->judul); ?>">
                            <?php echo e($banner->judul); ?>

                        </div>

                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-secondary" style="font-size:.65rem">Urutan <?php echo e($banner->urutan); ?></span>
                            
                            <button class="btn btn-sm btn-<?php echo e($banner->is_aktif ? 'success' : 'secondary'); ?> btn-xs toggle-btn py-0 px-2"
                                    data-id="<?php echo e($banner->id); ?>" style="font-size:.72rem">
                                <i class="fas fa-<?php echo e($banner->is_aktif ? 'eye' : 'eye-slash'); ?> me-1"></i>
                                <?php echo e($banner->is_aktif ? 'Aktif' : 'Nonaktif'); ?>

                            </button>
                        </div>

                        
                        <div class="d-flex gap-1">
                            <span class="drag-handle me-auto" title="Drag untuk ubah urutan">
                                <i class="fas fa-grip-vertical"></i>
                            </span>
                            <button class="btn btn-xs btn-outline-danger py-0 px-2 hapus-btn"
                                    data-id="<?php echo e($banner->id); ?>" data-judul="<?php echo e($banner->judul); ?>"
                                    style="font-size:.72rem">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// ── Preview sebelum upload ────────────────────────────────────
function previewGambar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewArea').classList.remove('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Submit button loading ─────────────────────────────────────
document.getElementById('uploadForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengupload...';
    btn.disabled  = true;
});

// ── Toggle aktif/nonaktif ─────────────────────────────────────
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        fetch(`/admin/banner/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const aktif = d.is_aktif;
                this.className = `btn btn-sm btn-${aktif ? 'success' : 'secondary'} btn-xs toggle-btn py-0 px-2`;
                this.innerHTML = `<i class="fas fa-${aktif ? 'eye' : 'eye-slash'} me-1"></i>${aktif ? 'Aktif' : 'Nonaktif'}`;
                this.style.fontSize = '.72rem';
            }
        });
    });
});

// ── Hapus banner ──────────────────────────────────────────────
document.querySelectorAll('.hapus-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id    = this.dataset.id;
        const judul = this.dataset.judul;
        if (!confirm(`Hapus banner "${judul}"?`)) return;

        fetch(`/admin/banner/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                this.closest('.banner-col').remove();
            }
        });
    });
});

// ── Drag & drop urutan (SortableJS) ──────────────────────────
const grid = document.getElementById('bannerGrid');
if (grid) {
    Sortable.create(grid, {
        handle: '.drag-handle',
        animation: 180,
        ghostClass: 'sortable-ghost',
        onEnd: function () {
            const urutan = {};
            document.querySelectorAll('.banner-col').forEach((el, i) => {
                urutan[el.dataset.id] = i + 1;
                el.querySelector('.badge').textContent = 'Urutan ' + (i + 1);
            });
            fetch('/admin/banner/urutan', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ urutan })
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/banner/index.blade.php ENDPATH**/ ?>