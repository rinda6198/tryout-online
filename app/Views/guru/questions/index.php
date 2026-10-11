<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0"><?= esc($package['title']) ?></h5>
        <small class="text-muted">
            <?= esc($package['subject_name'] ?? '') ?> &middot;
            Durasi <?= $package['duration'] ?> menit &middot;
            Total Soal: <?= count($questions) ?>
        </small>
    </div>

    <div>
        <a href="<?= base_url($base . '/packages/' . $package['id'] . '/questions/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Soal
        </a>

        <a href="<?= base_url($base . '/packages/' . $package['id'] . '/import') ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Import Excel
        </a>
        <a href="<?= base_url($base . '/packages') ?>" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <?php if (empty($questions)): ?>
            <p class="text-muted text-center mb-0">Belum ada soal pada paket ini. Klik "Tambah Soal" atau "Import Excel"
                untuk menambahkan.</p>
        <?php else: ?>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Soal</th>
                        <th style="width: 80px;">Poin</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($questions as $i => $q): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <?= substr(strip_tags($q['question_text']), 0, 150) ?>
                                <?= strlen($q['question_text']) > 150 ? '...' : '' ?>

                                <?php if (!empty($q['image'])): ?>
                                    <br>
                                    <img src="<?= base_url('uploads/questions/' . $q['image']) ?>" class="img-thumbnail mt-1"
                                        style="max-height: 80px;" alt="Gambar soal">
                                <?php endif; ?>

                                <?php if (!empty($q['options'])): ?>
                                    <div class="mt-2 small text-muted">
                                        <?php foreach ($q['options'] as $opt): ?>
                                            <div>
                                                <?= esc($opt['option_label']) ?>. <?= esc($opt['option_text']) ?>
                                                <?php if ((int) $opt['is_correct'] === 1): ?>
                                                    <span class="badge bg-success">Kunci</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= $q['points'] ?></td>
                            <td>
                                <a href="<?= base_url($base . '/questions/edit/' . $q['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete"
                                    data-url="<?= base_url($base . '/questions/delete/' . $q['id']) ?>">
                                    <i class="bi bi-trash"></i> Hapus
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

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('.btn-delete').on('click', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Hapus soal ini?',
                text: 'Pilihan jawaban terkait juga akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) window.location.href = url;
            });
        });
    });
</script>
<?= $this->endSection() ?>