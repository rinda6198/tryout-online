<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Try Out Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f7f8fa;
            --accent: #4f6df5;
            --accent-dark: #3d59d9;
            --accent-soft: #eaeefd;
            --border: #e6e8ec;
            --text-primary: #1f2330;
            --text-muted: #6b7280;
            --radius: 10px;
            --shadow-md: 0 6px 16px rgba(16, 24, 40, .07);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            max-width: 420px;
            margin: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
        }

        .brand-icon {
            width: 56px; height: 56px; border-radius: 50%;
            background-color: var(--accent-soft);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto .75rem;
        }
        .brand-icon i { font-size: 1.6rem; color: var(--accent); }

        h4 { font-weight: 600; letter-spacing: -.01em; }
        small.text-muted, .text-muted { color: var(--text-muted) !important; }

        .form-label { font-size: .875rem; font-weight: 500; }
        .form-control {
            border-color: var(--border);
            border-radius: 8px;
            padding: .55rem .75rem;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            border-radius: 8px;
            font-weight: 500;
            padding: .55rem 0;
        }
        .btn-primary:hover { background-color: var(--accent-dark); border-color: var(--accent-dark); }

        .alert { border-radius: 8px; border: none; }
        hr { border-color: var(--border); }
        a { color: var(--accent); text-decoration: none; font-weight: 500; }
        a:hover { color: var(--accent-dark); text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <div class="card login-card p-4">
        <div class="text-center mb-3">
            <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h4 class="mt-2 mb-0">Try Out Online</h4>
            <small class="text-muted">Silakan login untuk melanjutkan</small>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Username / Email</label>
                <input type="text" name="identity" class="form-control" value="<?= old('identity') ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <hr>
        <p class="text-center mb-0">Belum punya akun? <a href="<?= base_url('register') ?>">Daftar sebagai Peserta</a></p>

    </div>
</div>
</body>
</html>