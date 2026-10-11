<div class="nav-header">Menu Utama</div>
<a href="<?= base_url('admin/dashboard') ?>" class="<?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>

<div class="nav-header">Master Data</div>
<a href="<?= base_url('admin/users') ?>" class="<?= str_contains(uri_string(), 'admin/users') ? 'active' : '' ?>"><i class="bi bi-people-fill me-2"></i> Manajemen Pengguna</a>
<a href="<?= base_url('admin/subjects') ?>" class="<?= str_contains(uri_string(), 'admin/subjects') ? 'active' : '' ?>"><i class="bi bi-journal-bookmark-fill me-2"></i> Mata Pelajaran</a>

<div class="nav-header">Try Out</div>
<a href="<?= base_url('admin/packages') ?>" class="<?= str_contains(uri_string(), 'admin/packages') ? 'active' : '' ?>"><i class="bi bi-box-seam-fill me-2"></i> Paket Try Out</a>
<a href="<?= base_url('admin/results') ?>" class="<?= str_contains(uri_string(), 'admin/results') ? 'active' : '' ?>"><i class="bi bi-bar-chart-fill me-2"></i> Hasil Peserta</a>

<div class="nav-header">Lainnya</div>
<a href="<?= base_url('admin/announcements') ?>" class="<?= str_contains(uri_string(), 'admin/announcements') ? 'active' : '' ?>"><i class="bi bi-megaphone-fill me-2"></i> Pengumuman</a>
<a href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>