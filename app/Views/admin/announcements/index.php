<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pengumuman</h4>

    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i>
        Tambah Pengumuman
    </button>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif; ?>

<div class="row">

<?php if(empty($announcements)): ?>

<div class="col-12">
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            Belum ada pengumuman.
        </div>
    </div>
</div>

<?php endif; ?>

<?php foreach($announcements as $a): ?>

<div class="col-md-6 mb-3">

    <div class="card h-100 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                <?= esc($a['title']) ?>
            </strong>

            <?php if($a['is_active']): ?>
                <span class="badge bg-success">Aktif</span>
            <?php else: ?>
                <span class="badge bg-secondary">Nonaktif</span>
            <?php endif; ?>

        </div>

        <div class="card-body">

            <p style="white-space: pre-line">
                <?= esc($a['content']) ?>
            </p>

        </div>

        <div class="card-footer bg-white d-flex justify-content-between">

            <small class="text-muted">
                <?= date('d M Y H:i', strtotime($a['created_at'])) ?>
            </small>

            <div>

                <button
                    class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal<?= $a['id'] ?>">
                    Edit
                </button>

                <a href="<?= base_url('admin/announcements/delete/'.$a['id']) ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Hapus pengumuman ini?')">
                    Hapus
                </a>

            </div>

        </div>

    </div>

</div>


<!-- MODAL EDIT -->
<div class="modal fade" id="editModal<?= $a['id'] ?>" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form method="post"
              action="<?= base_url('admin/announcements/update/'.$a['id']) ?>">

            <?= csrf_field() ?>

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Pengumuman
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Judul
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="<?= esc($a['title']) ?>"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Isi Pengumuman
                        </label>

                        <textarea
                            name="content"
                            rows="6"
                            class="form-control"
                            required><?= esc($a['content']) ?></textarea>

                    </div>

                    <div class="form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="is_active"
                            value="1"
                            <?= $a['is_active'] ? 'checked' : '' ?>>

                        <label class="form-check-label">
                            Aktifkan Pengumuman
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<?php endforeach; ?>

</div>


<!-- MODAL TAMBAH -->
<div class="modal fade" id="addModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form method="post"
              action="<?= base_url('admin/announcements/store') ?>">

            <?= csrf_field() ?>

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Pengumuman
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Judul Pengumuman
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Isi Pengumuman
                        </label>

                        <textarea
                            name="content"
                            rows="6"
                            class="form-control"
                            required></textarea>

                    </div>

                    <div class="form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="is_active"
                            value="1"
                            checked>

                        <label class="form-check-label">
                            Aktifkan Pengumuman
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Tutup
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>