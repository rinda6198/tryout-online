<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header fw-semibold">Edit Paket Try Out</div>
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

        <form action="<?= base_url($base . '/packages/update/' . $package['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Judul Paket</label>
                    <input type="text" name="title" class="form-control" value="<?= old('title', $package['title']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select" required>
                        <?php foreach ($subjects as $subject) : ?>
                            <option value="<?= $subject['id'] ?>" <?= $subject['id'] == $package['subject_id'] ? 'selected' : '' ?>><?= esc($subject['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description', $package['description']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Durasi (menit)</label>
                    <input type="number" name="duration" class="form-control" value="<?= old('duration', $package['duration']) ?>" min="1" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Token Ujian</label>
                    <input type="text" name="token" class="form-control" maxlength="10" value="<?= old('token', $package['token']) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Passing Grade</label>
                    <input type="number" name="passing_grade" class="form-control" value="<?= old('passing_grade', $package['passing_grade']) ?>" min="0" max="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= $package['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $package['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="closed" <?= $package['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="<?= $package['start_time'] ? date('Y-m-d\TH:i', strtotime($package['start_time'])) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="<?= $package['end_time'] ? date('Y-m-d\TH:i', strtotime($package['end_time'])) : '' ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 form-check">
                    <input type="checkbox" name="is_random_question" class="form-check-input" id="rq" value="1" <?= $package['is_random_question'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="rq">Acak Urutan Soal</label>
                </div>
                <div class="col-md-6 mb-3 form-check">
                    <input type="checkbox" name="is_random_option" class="form-check-input" id="ro" value="1" <?= $package['is_random_option'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ro">Acak Urutan Jawaban</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= base_url($base . '/packages') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>