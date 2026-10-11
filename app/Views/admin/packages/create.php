<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header fw-semibold">Tambah Paket Try Out</div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url($base . '/packages/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Judul Paket</label>
                    <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($subjects as $subject) : ?>
                            <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Durasi (menit)</label>
                    <input type="number" name="duration" class="form-control" value="<?= old('duration', 60) ?>" min="1" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Token Ujian</label>
                    <input type="text" name="token" class="form-control" maxlength="10" placeholder="Opsional" value="<?= old('token') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Passing Grade</label>
                    <input type="number" name="passing_grade" class="form-control" value="<?= old('passing_grade', 70) ?>" min="0" max="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="<?= old('start_time') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="<?= old('end_time') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 form-check">
                    <input type="checkbox" name="is_random_question" class="form-check-input" id="rq" value="1" <?= old('is_random_question') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="rq">Acak Urutan Soal</label>
                </div>
                <div class="col-md-6 mb-3 form-check">
                    <input type="checkbox" name="is_random_option" class="form-check-input" id="ro" value="1" <?= old('is_random_option') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ro">Acak Urutan Jawaban</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url($base . '/packages') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>