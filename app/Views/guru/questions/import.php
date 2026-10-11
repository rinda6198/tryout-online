<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Import Soal Excel - <?= esc($package['title']) ?></span>
        <a href="<?= base_url($base . '/packages/' . $packageId . '/questions') ?>" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <strong>Format Excel:</strong>
            <p class="mb-1">Baris pertama adalah header (akan dilewati). Susunan kolom:</p>
            <table class="table table-sm table-bordered bg-white mb-2">
                <thead>
                    <tr>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                        <th>G</th>
                        <th>H</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pertanyaan</td>
                        <td>Opsi A</td>
                        <td>Opsi B</td>
                        <td>Opsi C</td>
                        <td>Opsi D</td>
                        <td>Opsi E</td>
                        <td>Kunci (A-E)</td>
                        <td>Poin</td>
                    </tr>
                </tbody>
            </table>
            <small>Semua kolom A-G wajib diisi. Kolom H (poin) opsional, default 1.</small>
        </div>

        <form method="post" enctype="multipart/form-data"
            action="<?= base_url($base . '/packages/' . $packageId . '/import') ?>">

            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">File Excel (.xlsx, .xls, .csv) - maks 5MB</label>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-upload"></i> Upload &amp; Import
            </button>
            <a href="<?= base_url($base . '/packages/' . $packageId . '/questions') ?>"
                class="btn btn-secondary">Batal</a>
        </form>

    </div>

</div>

<?= $this->endSection() ?>