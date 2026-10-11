<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        Riwayat Try Out
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Paket</th>
                    <th>Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($history)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada riwayat try out.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($history as $row) : ?>
                    <tr>
                        <td><?= esc($row['package_title']) ?></td>
                        <td>
                            <?php
                                // FIX: results bisa null jika join LEFT tidak menemukan baris results
                                // (misal exam finished tapi result gagal tersimpan)
                            ?>
                            <?= $row['score'] !== null ? number_format((float) $row['score'], 2) : '-' ?>
                        </td>
                        <td>
                            <?php if ($row['score'] === null) : ?>
                                <span class="badge bg-secondary">Belum Dinilai</span>
                            <?php elseif ((int) $row['is_passed'] === 1) : ?>
                                <span class="badge bg-success">Lulus</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Tidak Lulus</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('peserta/ranking/' . $row['package_id']) ?>" class="btn btn-primary btn-sm">
                                Ranking
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>