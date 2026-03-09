<?php $__env->startSection('title','Skripsi'); ?> <?php $__env->startSection('page-title','Manajemen Skripsi'); ?>
<?php $__env->startSection('content'); ?>


<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="<?php echo e(request('nim')); ?>">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        <?php if(request('nim')): ?><a href="<?php echo e(route('admin.skripsi.index')); ?>" class="btn btn-light">Reset</a><?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="skripsiTab">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-pengajuan">Pengajuan Skripsi (<?php echo e($pengajuans->total()); ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-bimbingan">Bimbingan (<?php echo e($bimbingans->total()); ?>)</a></li>
                </ul>
            </div>
            <div class="card-body tab-content">
                
                <div class="tab-pane fade show active" id="tab-pengajuan">
                    <form method="GET" class="row g-2 mb-3">
                        <input type="hidden" name="nim" value="<?php echo e(request('nim')); ?>">
                        <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <?php $__currentLoopData = ['Diajukan','Disetujui','Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>" <?php echo e(request('status')===$s?'selected':''); ?>><?php echo e($s); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                        <div class="col-md-2"><button class="btn btn-secondary btn-sm"><i class="fas fa-filter"></i></button></div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light"><tr><th>NIM / Nama</th><th>Judul Skripsi</th><th>Bidang</th><th>Status</th><th>Pembimbing</th><th>Tgl Pengajuan</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($p->nim); ?></strong><br><small class="text-muted"><?php echo e($p->mahasiswa?->nama); ?></small></td>
                                <td style="max-width:200px"><small><?php echo e($p->judul); ?></small></td>
                                <td><small><?php echo e($p->bidang_ilmu); ?></small></td>
                                <td>
                                    <?php $sc=['Diajukan'=>'warning','Disetujui'=>'success','Ditolak'=>'danger']; ?>
                                    <span class="badge bg-<?php echo e($sc[$p->status]??'secondary'); ?>"><?php echo e($p->status); ?></span>
                                </td>
                                <td>
                                    <small>1: <?php echo e($p->pembimbing1?->nama ?? '-'); ?></small><br>
                                    <small>2: <?php echo e($p->pembimbing2?->nama ?? '-'); ?></small>
                                </td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($p->tgl_pengajuan)->format('d/m/Y')); ?></small></td>
                                <td>
                                    <button class="btn btn-xs btn-primary btn-sm py-0 px-2" onclick="openStatusModal(<?php echo e($p->id); ?>,'<?php echo e($p->status); ?>','<?php echo e($p->judul); ?>')" data-bs-toggle="modal" data-bs-target="#statusModal">
                                        <i class="fas fa-edit me-1"></i>Proses
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada pengajuan</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($pengajuans->links('pagination::bootstrap-5')); ?>

                </div>

                
                <div class="tab-pane fade" id="tab-bimbingan">
                    <form method="GET" class="row g-2 mb-3">
                        <input type="hidden" name="nim_bimb" value="<?php echo e(request('nim')); ?>">
                        <div class="col-md-3"><button class="btn btn-secondary btn-sm"><i class="fas fa-sync"></i></button></div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light"><tr><th>NIM / Nama</th><th>Dosen</th><th>Bab</th><th>Tanggal</th><th>Status</th><th>Catatan Mhs</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($b->nim); ?></strong><br><small><?php echo e($b->mahasiswa?->nama); ?></small></td>
                                <td><small><?php echo e($b->dosen?->nama); ?></small></td>
                                <td><span class="badge bg-info"><?php echo e($b->bab); ?></span></td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($b->tanggal)->format('d/m/Y')); ?></small></td>
                                <td>
                                    <?php $sb=['Menunggu'=>'warning','Diterima'=>'success','Revisi'=>'danger']; ?>
                                    <span class="badge bg-<?php echo e($sb[$b->status]??'secondary'); ?>"><?php echo e($b->status); ?></span>
                                </td>
                                <td><small><?php echo e(Str::limit($b->catatan_mahasiswa, 50)); ?></small></td>
                                <td>
                                    <button class="btn btn-xs btn-warning btn-sm py-0 px-2" onclick="openBimbModal(<?php echo e($b->id); ?>,'<?php echo e($b->status); ?>')" data-bs-toggle="modal" data-bs-target="#bimbModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($bimbingans->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="statusForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Proses Pengajuan Skripsi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="small text-muted mb-3" id="modalJudul"></p>
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required id="modalStatus">
                            <option value="Diajukan">Diajukan</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Pembimbing 1</label>
                        <select name="pembimbing1_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            <?php $__currentLoopData = $dosenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>"><?php echo e($d->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Pembimbing 2</label>
                        <select name="pembimbing2_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            <?php $__currentLoopData = $dosenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>"><?php echo e($d->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Komentar / Catatan</label>
                        <textarea name="komentar_prodi" class="form-control" rows="3" placeholder="Opsional..."></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="bimbModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="bimbForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Update Status Bimbingan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required id="bimbStatus">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Revisi">Revisi</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Catatan Dosen</label>
                        <textarea name="catatan_dosen" class="form-control" rows="3" placeholder="Masukkan catatan/feedback..."></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function filterNIM() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const url = new URL(window.location.href);
    url.searchParams.set('nim', nim);
    window.location.href = url.toString();
}
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();filterNIM();} });
function openStatusModal(id, status, judul) {
    document.getElementById('statusForm').action = '/admin/skripsi/'+id+'/status';
    document.getElementById('modalStatus').value = status;
    document.getElementById('modalJudul').textContent = 'Judul: ' + judul;
}
function openBimbModal(id, status) {
    document.getElementById('bimbForm').action = '/admin/skripsi/bimbingan/'+id;
    document.getElementById('bimbStatus').value = status;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/skripsi/index.blade.php ENDPATH**/ ?>