<?php $__env->startSection('title', 'Detail Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Detail Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                
                <div class="position-relative d-inline-block mb-3">
                    <?php if($mahasiswa->foto): ?>
                        <img src="<?php echo e(Storage::disk('public')->url($mahasiswa->foto)); ?>"
                             alt="Foto" id="fotoPreview"
                             class="rounded-circle border border-3 border-primary object-fit-cover"
                             style="width:90px;height:90px;object-fit:cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto"
                             style="width:90px;height:90px" id="fotoPlaceholder">
                            <i class="fas fa-user text-white fa-2x"></i>
                        </div>
                        <img src="" alt="" id="fotoPreview" class="rounded-circle border border-3 border-primary d-none"
                             style="width:90px;height:90px;object-fit:cover;">
                    <?php endif; ?>
                    <label for="fotoInput" class="position-absolute bottom-0 end-0 bg-warning rounded-circle d-flex align-items-center justify-content-center"
                           style="width:28px;height:28px;cursor:pointer" title="Upload Foto">
                        <i class="fas fa-camera text-dark" style="font-size:.7rem"></i>
                    </label>
                    <input type="file" id="fotoInput" accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="d-none" onchange="uploadFoto(this)">
                </div>
                <div id="fotoStatus" class="small mb-2"></div>
                <h5 class="fw-bold"><?php echo e($mahasiswa->nama); ?></h5>
                <p class="text-muted"><?php echo e($mahasiswa->nim); ?></p>
                <span class="badge <?php echo e($mahasiswa->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary'); ?> mb-3">
                    <?php echo e($mahasiswa->status_aktif); ?>

                </span>
                <table class="table table-sm text-start">
                    <tr><td class="text-muted">Prodi</td><td><?php echo e($mahasiswa->prodi); ?></td></tr>
                    <tr><td class="text-muted">Kelas</td><td><?php echo e($mahasiswa->kelas); ?></td></tr>
                    <tr><td class="text-muted">Angkatan</td><td><?php echo e($mahasiswa->angkatan); ?></td></tr>
                    <tr><td class="text-muted">Semester</td><td><?php echo e($mahasiswa->semester_sekarang); ?></td></tr>
                    <tr><td class="text-muted">Dosen PA</td><td><?php echo e($mahasiswa->dosenPA?->nama ?? '-'); ?></td></tr>
                    <tr><td class="text-muted">Email</td><td><?php echo e($mahasiswa->email ?? '-'); ?></td></tr>
                </table>
                <a href="<?php echo e(route('admin.mahasiswa.edit', $mahasiswa->nim)); ?>" class="btn btn-warning btn-sm w-100">
                    <i class="fas fa-edit me-1"></i>Edit Data
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- KHS Summary -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-chart-line me-2"></i>Riwayat KHS</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Semester</th><th>Tahun Akademik</th><th>SKS</th><th>SKS Kumulatif</th><th>IPS</th><th>IPK</th></tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $mahasiswa->khs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($k->semester); ?></td>
                                <td><?php echo e($k->tahun_akademik); ?></td>
                                <td><?php echo e($k->total_sks); ?></td>
                                <td><?php echo e($k->total_sks_kumulatif); ?></td>
                                <td><span class="badge bg-info"><?php echo e(number_format($k->ips,2)); ?></span></td>
                                <td><span class="badge bg-primary"><?php echo e(number_format($k->ipk,2)); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada data KHS</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Point Book -->
        <?php if($mahasiswa->pointBook->count()): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-star me-2"></i>Point Book</span>
                <span class="badge bg-warning text-dark">Total: <?php echo e($mahasiswa->pointBook->sum('poin')); ?> poin</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Kegiatan</th><th>Tanggal</th><th>Poin</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $mahasiswa->pointBook; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($p->nama_kegiatan); ?></td>
                                <td><?php echo e($p->tanggal); ?></td>
                                <td><span class="badge bg-success">+<?php echo e($p->poin); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function uploadFoto(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    // Preview lokal dulu
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('fotoPreview').src = e.target.result;
        document.getElementById('fotoPreview').classList.remove('d-none');
        const ph = document.getElementById('fotoPlaceholder');
        if (ph) ph.classList.add('d-none');
    };
    reader.readAsDataURL(file);

    // Upload ke server
    const fd = new FormData();
    fd.append('foto', file);
    fd.append('_token', '<?php echo e(csrf_token()); ?>');
    const statusEl = document.getElementById('fotoStatus');
    statusEl.innerHTML = '<span class="text-primary"><i class="fas fa-spinner fa-spin me-1"></i>Mengupload...</span>';

    fetch('<?php echo e(route("admin.mahasiswa.upload-foto", $mahasiswa->nim)); ?>', {
        method: 'POST', body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            statusEl.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Foto berhasil diperbarui.</span>';
            document.getElementById('fotoPreview').src = d.foto_url;
        } else {
            statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>' + (d.message || 'Gagal upload.') + '</span>';
        }
    })
    .catch(() => {
        statusEl.innerHTML = '<span class="text-danger">Terjadi kesalahan jaringan.</span>';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/mahasiswa/show.blade.php ENDPATH**/ ?>