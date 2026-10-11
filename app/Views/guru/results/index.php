<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Hasil Peserta - Paket Try Out Saya</h5>
    </div>

    <div class="card-body">

        <?php if (empty($packages)) : ?>
            <p class="text-muted text-center mb-0">Anda belum memiliki paket try out. Buat paket terlebih dahulu di menu "Paket Try Out".</p>
        <?php else : ?>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Paket</th>
                    <th>Mapel</th>
                    <th>Status</th>
                    <th>Jml Soal</th>
                    <th>Jml Peserta</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($packages as $p) : ?>
                    <tr>
                        <td><?= esc($p['title']) ?></td>
                        <td><?= esc($p['subject_name'] ?? '-') ?></td>
                        <td>
                            <?php
                                $badge = match($p['status']) {
                                    'published' => 'success',
                                    'closed'    => 'secondary',
                                    default     => 'warning',
                                };
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= esc($p['status']) ?></span>
                        </td>
                        <td><?= $p['question_count'] ?></td>
                        <td><?= $p['participant_count'] ?></td>
                        <td>
                            <a href="<?= base_url('guru/results/package/' . $p['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-bar-chart"></i> Lihat Hasil
                            </a>
                            <a href="<?= base_url('guru/analysis/' . $p['id']) ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-clipboard-data"></i> Analisis
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
