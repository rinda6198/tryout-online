<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        Daftar Try Out
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Paket</th>
                    <th>Mapel</th>
                    <th>Durasi</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($packages)) : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada try out yang tersedia.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($packages as $package) : ?>
                    <tr>
                        <td><?= esc($package['title']) ?></td>
                        <td><?= esc($package['subject_name']) ?></td>
                        <td><?= $package['duration'] ?> menit</td>
                        <td>
                            <?php if (! empty($package['start_time']) || ! empty($package['end_time'])) : ?>
                                <small>
                                    <?= $package['start_time'] ? date('d/m/Y H:i', strtotime($package['start_time'])) : '-' ?>
                                    s/d
                                    <?= $package['end_time'] ? date('d/m/Y H:i', strtotime($package['end_time'])) : '-' ?>
                                </small>
                            <?php else : ?>
                                <small class="text-muted">Tanpa batas waktu</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                // FIX: gunakan form POST + CSRF, bukan link <a> GET,
                                // karena route start() sekarang POST untuk keamanan.
                            ?>
                            <form action="<?= base_url('peserta/tryout/start/' . $package['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-play-fill"></i> Mulai
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>