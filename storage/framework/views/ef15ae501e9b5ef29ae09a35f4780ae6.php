<?php $__env->startSection('title','Kehadiran'); ?> <?php $__env->startSection('page-title','Manajemen Kehadiran'); ?>
<?php $__env->startSection('content'); ?>


<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari & Filter by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Masukkan NIM mahasiswa..." value="<?php echo e(request('nim')); ?>">
        </div>
        <button class="btn btn-warning" onclick="filterByNIM()"><i class="fas fa-search me-1"></i>Tampilkan</button>
        <?php if(request('nim')): ?><a href="<?php echo e(route('admin.kehadiran.index')); ?>" class="btn btn-light">Reset</a><?php endif; ?>
    </div>
    <?php if($rekap): ?>
    <div class="mt-2 row g-2">
        <?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $pct = $r->total > 0 ? round(($r->hadir/$r->total)*100) : 0; ?>
        <div class="col-md-6 col-lg-4">
            <div class="alert alert-light py-2 mb-0">
                <strong><?php echo e($r->kode_matkul); ?></strong> – <?php echo e($r->mataKuliah?->nama_mk); ?>

                <div class="d-flex gap-2 mt-1 flex-wrap small">
                    <span class="badge bg-success">Hadir: <?php echo e($r->hadir); ?></span>
                    <span class="badge bg-info">Sakit: <?php echo e($r->sakit); ?></span>
                    <span class="badge bg-warning text-dark">Izin: <?php echo e($r->izin); ?></span>
                    <span class="badge bg-danger">Alpha: <?php echo e($r->alpha); ?></span>
                    <span class="badge bg-primary"><?php echo e($pct); ?>%</span>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>

<div class="row g-3">
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user-check me-2 text-success"></i>Input Kehadiran</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.kehadiran.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <div class="d-flex gap-1">
                            <input type="text" name="nim" id="nimInput" class="form-control" value="<?php echo e(old('nim', request('nim'))); ?>" required placeholder="22.240.0001">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="searchNIMKehadiran()"><i class="fas fa-search"></i></button>
                        </div>
                        <div id="nimInfo" class="mt-1"></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah</label>
                        <select name="kode_matkul" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            <?php $__currentLoopData = $mkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(old('kode_matkul')===$mk->kode_mk?'selected':''); ?>><?php echo e($mk->kode_mk); ?> - <?php echo e($mk->nama_mk); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-5"><label class="form-label fw-semibold">Pertemuan ke-</label>
                            <input type="number" name="pertemuan" class="form-control" value="<?php echo e(old('pertemuan',1)); ?>" min="1" required></div>
                        <div class="col-7"><label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <?php $__currentLoopData = ['Hadir','Tidak Hadir','Sakit','Izin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php echo e(old('status')===$s?'selected':''); ?>><?php echo e($s); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?php echo e(old('keterangan')); ?>" placeholder="Opsional"></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-users me-2 text-info"></i>Input Massal (per Kelas)</div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.kehadiran.bulk-form')); ?>">
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah</label>
                        <select name="kode_matkul" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            <?php $__currentLoopData = $mkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->kode_mk); ?>"><?php echo e($mk->kode_mk); ?> - <?php echo e($mk->nama_mk); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="IF-2022-A" required></div>
                    <button type="submit" class="btn btn-info w-100 text-white"><i class="fas fa-list-check me-1"></i>Buka Form Massal</button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-table me-2"></i>Data Kehadiran (<?php echo e($kehadirans->total()); ?>)</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <input type="hidden" name="nim" value="<?php echo e(request('nim')); ?>">
                    <div class="col-md-4"><select name="kode_matkul" class="form-select form-select-sm">
                        <option value="">Semua MK</option>
                        <?php $__currentLoopData = $mkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mk->kode_mk); ?>" <?php echo e(request('kode_matkul')===$mk->kode_mk?'selected':''); ?>><?php echo e($mk->kode_mk); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                    <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <?php $__currentLoopData = ['Hadir','Tidak Hadir','Sakit','Izin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status')===$s?'selected':''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>MK</th><th>Pertemuan</th><th>Status</th><th>Tanggal</th><th>Hapus</th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $kehadirans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($k->nim); ?></td>
                            <td><small><?php echo e($k->mahasiswa?->nama); ?></small></td>
                            <td><small><?php echo e($k->kode_matkul); ?></small></td>
                            <td class="text-center"><?php echo e($k->pertemuan); ?></td>
                            <td>
                                <?php $colors = ['Hadir'=>'success','Tidak Hadir'=>'danger','Sakit'=>'info','Izin'=>'warning']; ?>
                                <span class="badge bg-<?php echo e($colors[$k->status] ?? 'secondary'); ?>"><?php echo e($k->status); ?></span>
                            </td>
                            <td><small><?php echo e(\Carbon\Carbon::parse($k->tanggal)->format('d/m/Y')); ?></small></td>
                            <td>
                                <form action="<?php echo e(route('admin.kehadiran.destroy',$k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kehadiran?')">
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
                <?php echo e($kehadirans->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
function filterByNIM() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const url = new URL(window.location.href);
    url.searchParams.set('nim', nim);
    window.location.href = url.toString();
}
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if (e.key==='Enter'){e.preventDefault();filterByNIM();} });
function searchNIMKehadiran() {
    const nim = document.getElementById('nimInput').value.trim();
    if (!nim) return;
    fetch('<?php echo e(route("admin.kehadiran.search-mhs")); ?>?nim='+encodeURIComponent(nim), {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}
    }).then(r=>r.json()).then(data=>{
        const el = document.getElementById('nimInfo');
        if (data.success && data.data) {
            el.innerHTML='<div class="alert alert-info py-1 small mb-0"><i class="fas fa-user me-1"></i><strong>'+data.data.nama+'</strong> – '+data.data.kelas+'</div>';
        } else {
            el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/kehadiran/index.blade.php ENDPATH**/ ?>