<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        Analisis Soal
        <small class="text-muted">(<?= esc($package['title']) ?>)</small>
    </h5>
    <a href="<?= base_url('guru/results') ?>" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="alert alert-info">
    Total peserta selesai: <strong><?= $totalFinishedExams ?></strong>
</div>

<?php if (empty($analysis)) : ?>
    <div class="card">
        <div class="card-body text-center text-muted">
            Paket ini belum memiliki soal.
        </div>
    </div>
<?php endif; ?>

<?php foreach ($analysis as $i => $a) : ?>

    <div class="card mb-4">

        <div class="card-header">
            <strong>Soal <?= $i + 1 ?></strong>
        </div>

        <div class="card-body">

            <p><?= esc($a['question']['question_text']) ?></p>

            <?php if (! empty($a['question']['image'])) : ?>
                <img src="<?= base_url('uploads/questions/' . $a['question']['image']) ?>" class="img-thumbnail mb-2" style="max-height: 150px;" alt="Gambar soal">
            <?php endif; ?>

            <div class="row">

                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <strong><?= $a['correct_percentage'] ?>%</strong>
                        <br>
                        Persen Benar
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <strong><?= $a['correct_count'] ?></strong>
                        <br>
                        Jawaban Benar
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <?php
                            $badge = match($a['difficulty']) {
                                'Mudah'  => 'success',
                                'Sedang' => 'warning',
                                'Sulit'  => 'danger',
                                default  => 'secondary',
                            };
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= esc($a['difficulty']) ?></span>
                        <br>
                        Tingkat Kesulitan
                    </div>
                </div>

            </div>

            <hr>

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Pilihan</th>
                        <th>Jawaban</th>
                        <th>Jumlah Memilih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($a['distribution'] as $d) : ?>
                        <tr class="<?= $d['is_correct'] ? 'table-success' : '' ?>">
                            <td><?= esc($d['label']) ?></td>
                            <td><?= esc($d['text']) ?></td>
                            <td><?= $d['count'] ?></td>
                            <td>
                                <?php if ($d['is_correct']) : ?>
                                    <span class="badge bg-success">Kunci Jawaban</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

            <?php if ($a['total_answered'] === 0) : ?>
                <p class="text-muted mb-0">Belum ada peserta yang menjawab soal ini.</p>
            <?php endif; ?>

        </div>

    </div>

<?php endforeach; ?>

<?= $this->endSection() ?>
