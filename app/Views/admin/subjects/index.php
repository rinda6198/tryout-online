<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Mata Pelajaran</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="bi bi-plus-circle"></i> Tambah Mata Pelajaran</button>
</div>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <table id="subjectsTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $i => $subject) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($subject['code']) ?></td>
                        <td><?= esc($subject['name']) ?></td>
                        <td><?= esc($subject['description']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $subject['id'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/subjects/delete/' . $subject['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $subject['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="<?= base_url('admin/subjects/update/' . $subject['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode</label>
                                            <input type="text" name="code" class="form-control" value="<?= esc($subject['code']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control" value="<?= esc($subject['name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3"><?= esc($subject['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/subjects/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: MTK" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#subjectsTable').DataTable();

        $('.btn-delete').on('click', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data soal & paket terkait mata pelajaran ini bisa terdampak.',
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