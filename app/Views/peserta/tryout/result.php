<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card text-center">
    <div class="card-body">
        <h2>Hasil Try Out</h2>
        <hr>

        <h1 class="display-1">
            <?= number_format((float) $result['score'], 2) ?>
        </h1>

        <div class="row justify-content-center mt-3">
            <div class="col-md-3">
                <div class="border rounded p-2">
                    <strong>Total Soal</strong><br>
                    <?= (int) $result['total_questions'] ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-success">
                    <strong>Benar</strong><br>
                    <?= (int) $result['correct_answers'] ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-danger">
                    <strong>Salah</strong><br>
                    <?= (int) $result['wrong_answers'] ?>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <?php if ((int) $result['is_passed'] === 1) : ?>
                <div class="alert alert-success">LULUS</div>
            <?php else : ?>
                <div class="alert alert-danger">TIDAK LULUS</div>
            <?php endif; ?>
        </div>

        <?php if (! empty($rankInfo) && $rankInfo['rank'] !== null) : ?>
            <div class="alert alert-info">
                Ranking Anda: <strong>#<?= $rankInfo['rank'] ?></strong> dari <?= $rankInfo['total'] ?> peserta
            </div>
        <?php endif; ?>

        <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-primary">
            Kembali
        </a>
        <a href="<?= base_url('peserta/ranking/' . $result['package_id']) ?>" class="btn btn-outline-primary">
            Lihat Ranking Lengkap
        </a>
    </div>
</div>

<?= $this->endSection() ?>