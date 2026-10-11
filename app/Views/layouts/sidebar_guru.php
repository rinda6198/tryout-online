<div class="nav-header">Menu Utama</div>
<a href="<?= base_url('guru/dashboard') ?>" class="<?= uri_string() == 'guru/dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>

<div class="nav-header">Try Out</div>
<a href="<?= base_url('guru/packages') ?>" class="<?= str_contains(uri_string(), 'guru/packages') ? 'active' : '' ?>"><i class="bi bi-box-seam-fill me-2"></i> Paket Try Out</a>
<a href="<?= base_url('guru/results') ?>" class="<?= str_contains(uri_string(), 'guru/results') ? 'active' : '' ?>"><i class="bi bi-bar-chart-fill me-2"></i> Hasil Peserta</a>

<div class="nav-header">Akun</div>
<a href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>