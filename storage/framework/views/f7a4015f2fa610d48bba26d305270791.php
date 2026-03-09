<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SIAKAD'); ?> - IWP Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <style>
        :root{--primary:#1a3a5c;--secondary:#2d6a9f;--accent:#f39c12}
        body{background:#f0f2f5;font-family:'Segoe UI',sans-serif}
        .sidebar{width:260px;height:100vh;background:var(--primary);position:fixed;top:0;left:0;z-index:1000;transition:.3s;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.2) transparent}
        .sidebar-brand{padding:1.25rem 1rem;border-bottom:1px solid rgba(255,255,255,.1)}
        .sidebar-brand h5{color:#fff;font-weight:700;margin:0;font-size:.95rem}
        .sidebar-brand p{color:rgba(255,255,255,.6);font-size:.72rem;margin:0}
        .sidebar-menu{padding:.25rem 0 2rem}
        .sidebar-menu a{display:flex;align-items:center;gap:.65rem;padding:.55rem 1.2rem;color:rgba(255,255,255,.72);text-decoration:none;font-size:.82rem;transition:.15s;border-left:3px solid transparent}
        .sidebar-menu a:hover,.sidebar-menu a.active{background:rgba(255,255,255,.09);color:#fff;border-left-color:var(--accent)}
        .sidebar-menu a i{width:16px;text-align:center;opacity:.8}
        .sidebar-menu .menu-header{padding:.6rem 1.2rem .3rem;color:rgba(255,255,255,.35);font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-top:.4rem}
        .main-content{margin-left:260px;transition:.3s;min-height:100vh}
        .topbar{background:#fff;padding:.7rem 1.5rem;box-shadow:0 1px 4px rgba(0,0,0,.08);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:999}
        .topbar .page-title{font-weight:600;color:var(--primary);margin:0;font-size:1rem}
        .page-content{padding:1.25rem}
        .card{border:none;box-shadow:0 1px 4px rgba(0,0,0,.08);border-radius:.5rem}
        .card-header{background:#fff;border-bottom:1px solid #e9ecef;font-weight:600;padding:.75rem 1rem}
        .stat-card{border-radius:.5rem;padding:1.2rem;color:#fff}
        .table th{font-size:.8rem;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.3px;white-space:nowrap}
        .table td{font-size:.855rem;vertical-align:middle}
        .btn-xs{padding:.15rem .45rem;font-size:.75rem}
        .badge{font-size:.72rem}
        .form-label{font-size:.855rem}
        .nim-search-box{background:linear-gradient(135deg,#1a3a5c,#2d6a9f);border-radius:.5rem;padding:1rem;margin-bottom:1rem}
        @media(max-width:768px){.sidebar{transform:translateX(-100%)}.main-content{margin-left:0}.sidebar.show{transform:translateX(0)}}
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="fas fa-graduation-cap me-2"></i>SIAKAD IWP</h5>
        <p>Panel Administrator &bull; <?php echo e(session('admin_role','admin')); ?></p>
    </div>
    <div class="sidebar-menu">
        <div class="menu-header">Utama</div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="menu-header">Data Master</div>
        <a href="<?php echo e(route('admin.mahasiswa.index')); ?>" class="<?php echo e(request()->routeIs('admin.mahasiswa.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-graduate"></i> Mahasiswa
        </a>
        <a href="<?php echo e(route('admin.dosen.index')); ?>" class="<?php echo e(request()->routeIs('admin.dosen.*') ? 'active' : ''); ?>">
            <i class="fas fa-chalkboard-teacher"></i> Dosen
        </a>
        <a href="<?php echo e(route('admin.matakuliah.index')); ?>" class="<?php echo e(request()->routeIs('admin.matakuliah.*') ? 'active' : ''); ?>">
            <i class="fas fa-book"></i> Mata Kuliah
        </a>
        <a href="<?php echo e(route('admin.kurikulum.index')); ?>" class="<?php echo e(request()->routeIs('admin.kurikulum.*') ? 'active' : ''); ?>">
            <i class="fas fa-sitemap"></i> Kurikulum
        </a>

        <div class="menu-header">Perkuliahan</div>
        <a href="<?php echo e(route('admin.krs-setting.index')); ?>" class="<?php echo e(request()->routeIs('admin.krs-setting.*') ? 'active' : ''); ?>">
            <i class="fas fa-key"></i> Setting KRS
        </a>
        <a href="<?php echo e(route('admin.krs.index')); ?>" class="<?php echo e(request()->routeIs('admin.krs.*') ? 'active' : ''); ?>">
            <i class="fas fa-clipboard-list"></i> KRS Mahasiswa
        </a>
        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="<?php echo e(request()->routeIs('admin.jadwal.*') ? 'active' : ''); ?>">
            <i class="fas fa-calendar-alt"></i> Jadwal Kuliah
        </a>
        <a href="<?php echo e(route('admin.ujian.index')); ?>" class="<?php echo e(request()->routeIs('admin.ujian.*') ? 'active' : ''); ?>">
            <i class="fas fa-file-alt"></i> Jadwal Ujian
        </a>
        <a href="<?php echo e(route('admin.kehadiran.index')); ?>" class="<?php echo e(request()->routeIs('admin.kehadiran.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-check"></i> Kehadiran
        </a>
        <a href="<?php echo e(route('admin.nilai.index')); ?>" class="<?php echo e(request()->routeIs('admin.nilai.*') ? 'active' : ''); ?>">
            <i class="fas fa-star-half-alt"></i> Input Nilai
        </a>

        <div class="menu-header">Keuangan</div>
        <a href="<?php echo e(route('admin.keuangan.index')); ?>" class="<?php echo e(request()->routeIs('admin.keuangan.*') ? 'active' : ''); ?>">
            <i class="fas fa-money-bill-wave"></i> Tagihan & Pembayaran
        </a>

        <div class="menu-header">Layanan</div>
        <a href="<?php echo e(route('admin.skripsi.index')); ?>" class="<?php echo e(request()->routeIs('admin.skripsi.*') ? 'active' : ''); ?>">
            <i class="fas fa-scroll"></i> Skripsi
        </a>
        <a href="<?php echo e(route('admin.magang.index')); ?>" class="<?php echo e(request()->routeIs('admin.magang.*') ? 'active' : ''); ?>">
            <i class="fas fa-briefcase"></i> Magang
        </a>
        <a href="<?php echo e(route('admin.perpustakaan.index')); ?>" class="<?php echo e(request()->routeIs('admin.perpustakaan.*') ? 'active' : ''); ?>">
            <i class="fas fa-book-open"></i> Perpustakaan
        </a>
        <a href="<?php echo e(route('admin.pointbook.index')); ?>" class="<?php echo e(request()->routeIs('admin.pointbook.*') ? 'active' : ''); ?>">
            <i class="fas fa-medal"></i> Point Book
        </a>

        <div class="menu-header">Informasi</div>
        <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="<?php echo e(request()->routeIs('admin.pengumuman.*') ? 'active' : ''); ?>">
            <i class="fas fa-bullhorn"></i> Pengumuman
        </a>
        <a href="<?php echo e(route('admin.banner.index')); ?>" class="<?php echo e(request()->routeIs('admin.banner.*') ? 'active' : ''); ?>">
            <i class="fas fa-images"></i> Banner Kegiatan
        </a>
        <a href="<?php echo e(route('admin.kalender.index')); ?>" class="<?php echo e(request()->routeIs('admin.kalender.*') ? 'active' : ''); ?>">
            <i class="fas fa-calendar-check"></i> Kalender Akademik
        </a>
        <a href="<?php echo e(route('admin.download.index')); ?>" class="<?php echo e(request()->routeIs('admin.download.*') ? 'active' : ''); ?>">
            <i class="fas fa-download"></i> Download Materi
        </a>
        <a href="<?php echo e(route('admin.notifikasi.index')); ?>" class="<?php echo e(request()->routeIs('admin.notifikasi.*') ? 'active' : ''); ?>">
            <i class="fas fa-bell"></i> Notifikasi
        </a>
        <a href="<?php echo e(route('admin.pengaduan.index')); ?>" class="<?php echo e(request()->routeIs('admin.pengaduan.*') ? 'active' : ''); ?>">
            <i class="fas fa-envelope-open-text"></i> Pengaduan
        </a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <h6 class="page-title"><?php echo $__env->yieldContent('page-title','Dashboard'); ?></h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-inline"><?php echo e(session('admin_nama')); ?></span>
            <span class="badge bg-primary"><?php echo e(strtoupper(session('admin_role','admin'))); ?></span>
            <form action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-outline-danger" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>
    <div class="page-content">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $('.select2').select2({theme:'bootstrap-5',width:'100%'});
});
// NIM Search Helper
function searchNIM(inputId, resultId, url) {
    const nim = document.getElementById(inputId).value.trim();
    if (nim.length < 3) return;
    fetch(url + '?nim=' + encodeURIComponent(nim), {
        headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
    }).then(r => r.json()).then(data => {
        const el = document.getElementById(resultId);
        if (data.success && data.data) {
            el.innerHTML = '<div class="alert alert-info py-2 mb-0"><strong>' + data.data.nama + '</strong> &bull; ' + data.data.kelas + ' &bull; Sem ' + data.data.semester_sekarang + '</div>';
        } else { el.innerHTML = '<div class="alert alert-warning py-2 mb-0">NIM tidak ditemukan</div>'; }
    }).catch(() => {});
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\kuliah\smt7\siakad test\siakad\siakad_backend_fix\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>