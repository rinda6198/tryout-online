<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        Ranking Peserta <?= isset($package) ? '- ' . esc($package['title']) : '' ?>
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Nilai</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($ranking)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data ranking.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($ranking as $i => $row) : ?>
                    <?php
                        // FIX: highlight baris milik peserta yang sedang login
                        $isCurrentUser = isset($currentUserId) && (int) $row['user_id'] === (int) $currentUserId;
                    ?>
                    <tr class="<?= $isCurrentUser ? 'table-primary fw-bold' : '' ?>">
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= esc($row['user_name']) ?>
                            <?php if ($isCurrentUser) : ?>
                                <span class="badge bg-primary">Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['username']) ?></td>
                        <td><?= number_format((float) $row['score'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>