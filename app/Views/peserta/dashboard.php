<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">

    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header">
                <strong><i class="bi bi-megaphone-fill text-warning"></i> Pengumuman</strong>
            </div>

            <div class="card-body">

                <?php if (empty($announcements)): ?>

                    <p class="text-muted mb-0">
                        Belum ada pengumuman.
                    </p>

                <?php else: ?>

                    <?php foreach ($announcements as $a): ?>

                        <div class="border-bottom pb-2 mb-3">

                            <h6>
                                <?= esc($a['title']) ?>
                            </h6>

                            <p class="mb-0">
                                <?= nl2br(esc($a['content'])) ?>
                            </p>

                        </div>

                    <?php endforeach ?>

                <?php endif ?>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-stat bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $totalTryout ?></h3>
                    <small>Try Out Diikuti</small>
                </div>
                <i class="bi bi-journal-check fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $highestScore !== null ? number_format((float) $highestScore, 2) : '-' ?></h3>
                    <small>Nilai Tertinggi</small>
                </div>
                <i class="bi bi-trophy-fill fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $lastRank !== null ? '#' . $lastRank : '-' ?></h3>
                    <small>Ranking Terakhir</small>
                </div>
                <i class="bi bi-bar-chart-fill fs-1"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Paket Try Out Tersedia
            </div>

            <div class="card-body">
                <div class="row">
                    <?php if (empty($packages)): ?>
                        <p class="text-muted text-center">Belum ada try out yang tersedia saat ini.</p>
                    <?php endif; ?>

                    <?php foreach ($packages as $package): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5><?= esc($package['title']) ?></h5>
                                    <p class="mb-1"><?= esc($package['subject_name']) ?></p>
                                    <p>Durasi: <?= $package['duration'] ?> menit</p>

                                    <?php
                                    // FIX: gunakan form POST + CSRF (route start() sekarang POST)
                                    ?>
                                    <form action="<?= base_url('peserta/tryout/start/' . $package['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-primary">
                                            Mulai Try Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Riwayat Nilai
            </div>

            <div class="card-body">
                <?php if (empty($history)): ?>
                    <p class="text-muted text-center mb-0">Belum ada riwayat try out.</p>
                <?php endif; ?>

                <?php foreach ($history as $row): ?>
                    <div class="border-bottom pb-2 mb-2">
                        <strong><?= esc($row['package_title']) ?></strong><br>
                        Nilai:
                        <span class="badge bg-primary">
                            <?= $row['score'] !== null ? number_format((float) $row['score'], 2) : '-' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>