<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= esc($package['title']) ?></h5>
    <a href="<?= base_url('guru/results') ?>" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row text-center g-3">

            <div class="col-md-3">
                <div class="border rounded p-3">
                    <h4 class="mb-0"><?= $stats['total_peserta'] ?></h4>
                    <small>Total Peserta</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3">
                    <h4 class="mb-0"><?= number_format($stats['avg_score'], 2) ?></h4>
                    <small>Rata-rata Nilai</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3">
                    <h4 class="mb-0"><?= number_format($stats['max_score'], 2) ?></h4>
                    <small>Nilai Tertinggi</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="border rounded p-3">
                    <h4 class="mb-0"><?= $stats['passed_count'] ?> / <?= $stats['total_peserta'] ?></h4>
                    <small>Lulus</small>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Ranking Peserta</span>
        <a href="<?= base_url('guru/analysis/' . $package['id']) ?>" class="btn btn-sm btn-success">
            <i class="bi bi-clipboard-data"></i> Analisis Soal
        </a>
    </div>

    <div class="card-body">

        <?php if (empty($results)) : ?>
            <p class="text-muted text-center mb-0">Belum ada peserta yang mengikuti try out ini.</p>
        <?php else : ?>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($results as $i => $r) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($r['user_name']) ?></td>
                        <td><?= esc($r['username']) ?></td>
                        <td><?= $r['correct_answers'] ?></td>
                        <td><?= $r['wrong_answers'] ?></td>
                        <td><strong><?= number_format((float) $r['score'], 2) ?></strong></td>
                        <td>
                            <?php if ((int) $r['is_passed'] === 1) : ?>
                                <span class="badge bg-success">Lulus</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Tidak Lulus</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
