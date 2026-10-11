<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Paket Try Out</h5>
    <a href="<?= base_url($base . '/packages/create') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Paket</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="packagesTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Mata Pelajaran</th>
                    <th>Durasi</th>
                    <th>Jml Soal</th>
                    <th>Token</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $i => $pkg) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($pkg['title']) ?></td>
                        <td><?= esc($pkg['subject_name']) ?></td>
                        <td><?= esc($pkg['duration']) ?> menit</td>
                        <td><?= $pkg['question_count'] ?></td>
                        <td><?= $pkg['token'] ? esc($pkg['token']) : '-' ?></td>
                        <td>
                            <?php
                                $badge = match($pkg['status']) {
                                    'published' => 'success',
                                    'closed' => 'secondary',
                                    default => 'warning',
                                };
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= esc($pkg['status']) ?></span>
                        </td>
                        <td class="text-nowrap">
                            <a href="<?= base_url($base . '/packages/' . $pkg['id'] . '/questions') ?>" class="btn btn-sm btn-info" title="Kelola Soal"><i class="bi bi-list-check"></i></a>
                            <a href="<?= base_url($base . '/packages/edit/' . $pkg['id']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url($base . '/packages/delete/' . $pkg['id']) ?>" title="Hapus"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#packagesTable').DataTable();

        $('.btn-delete').on('click', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Hapus paket?',
                text: 'Semua soal dan hasil terkait paket ini akan ikut terhapus!',
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