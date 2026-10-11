<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Try Out Online') ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f7f8fa;
            --sidebar-bg: #1b2231;
            --sidebar-hover: #262f43;
            --sidebar-text: #97a1b8;
            --accent: #4f6df5;
            --accent-dark: #3d59d9;
            --border: #e6e8ec;
            --text-primary: #1f2330;
            --text-muted: #6b7280;
            --radius: 10px;
            --shadow-sm: 0 1px 2px rgba(16, 24, 40, .05);
            --shadow-md: 0 6px 16px rgba(16, 24, 40, .07);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text-primary);
            font-size: .9375rem;
        }

        .wrapper { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 260px; min-height: 100vh; background-color: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; transition: left .25s ease; z-index: 1030;
        }
        .sidebar .brand {
            display: flex; align-items: center; gap: .5rem;
            padding: 1.15rem 1.25rem; font-size: 1.02rem; font-weight: 600;
            color: #fff; letter-spacing: -.01em;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar .brand i { color: var(--accent); font-size: 1.1rem; }
        .sidebar a {
            color: var(--sidebar-text); text-decoration: none; display: flex; align-items: center;
            gap: .65rem; padding: .65rem 1.25rem; font-size: .875rem; font-weight: 500;
            border-left: 3px solid transparent; transition: background-color .15s ease, color .15s ease;
        }
        .sidebar a:hover { background-color: var(--sidebar-hover); color: #fff; }
        .sidebar a.active {
            background-color: var(--sidebar-hover); color: #fff;
            border-left-color: var(--accent);
        }
        .sidebar .nav-header {
            padding: 1.1rem 1.25rem .35rem; font-size: .7rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .06em; color: #5b6478;
        }

        /* CONTENT */
        .content-wrapper { margin-left: 260px; width: calc(100% - 260px); }

        .topbar {
            background: #fff; border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm); padding-top: .55rem; padding-bottom: .55rem;
        }
        .topbar .fw-semibold { color: var(--text-primary); }
        .topbar small.text-muted { color: var(--text-muted) !important; }

        /* COMPONENTS */
        .card-stat { border-radius: var(--radius); color: #fff; border: none; box-shadow: var(--shadow-md); }
        .card { border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); }

        .btn-primary { background-color: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background-color: var(--accent-dark); border-color: var(--accent-dark); }

        .dropdown-menu { border: 1px solid var(--border); box-shadow: var(--shadow-md); border-radius: 8px; }

        .alert { border-radius: var(--radius); border: none; box-shadow: var(--shadow-sm); }

        /* Samakan semua elemen "primary" (badge, border, link, teks) dengan warna aksen,
           supaya tidak ada dua biru berbeda (biru default Bootstrap vs aksen sidebar) */
        .bg-primary { background-color: var(--accent) !important; }
        .text-primary { color: var(--accent) !important; }
        .border-primary { border-color: var(--accent) !important; }
        a { color: var(--accent); }
        a:hover { color: var(--accent-dark); }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        .table thead th {
            background-color: #fafbfc;
            border-bottom-width: 1px;
            font-size: .8125rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .table { border-color: var(--border); }

        .badge { font-weight: 500; border-radius: 6px; }

        .modal-content { border-radius: var(--radius); border: none; box-shadow: var(--shadow-md); }
        .modal-header, .modal-footer { border-color: var(--border); }

        .rounded { border-radius: var(--radius) !important; }

        .form-control, .form-select {
            border-color: var(--border);
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 109, 245, .12);
        }

        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.show { left: 0; }
            .content-wrapper { margin-left: 0; width: 100%; }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
<div class="wrapper">

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><i class="bi bi-mortarboard-fill"></i> Try Out Online</div>
        <?= $this->include('layouts/sidebar_' . (session()->get('role_slug') ?? 'guest')) ?>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="content-wrapper">
        <!-- TOPBAR -->
        <nav class="navbar navbar-expand topbar px-3">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <span class="ms-2 fw-semibold"><?= esc($title ?? '') ?></span>
            <div class="ms-auto dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <span><?= esc(session()->get('name')) ?> <small class="text-muted">(<?= esc(session()->get('role_name')) ?>)</small></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div class="container-fluid p-4">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= esc(session()->getFlashdata('success'), 'js') ?>', timer: 2500, showConfirmButton: false });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= esc(session()->getFlashdata('error'), 'js') ?>' });
    <?php endif; ?>
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>