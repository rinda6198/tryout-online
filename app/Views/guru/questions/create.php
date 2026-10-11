<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Tambah Soal - <?= esc($package['title']) ?></span>
       <a href="<?= base_url($base . '/packages/' . $packageId . '/questions') ?>"
   class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

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

        <form
            method="post"
            enctype="multipart/form-data"
            action="<?= base_url($base . '/packages/' . $packageId . '/questions/store') ?>">

            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Pertanyaan</label>
                <textarea
                    name="question_text"
                    class="form-control"
                    rows="4"
                    required><?= old('question_text') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Poin</label>
                <input
                    type="number"
                    name="points"
                    value="<?= old('points', 1) ?>"
                    min="1"
                    class="form-control">
            </div>

            <hr>
            <h6>Pilihan Jawaban</h6>

            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $label) : ?>
                <div class="mb-3">
                    <label class="form-label">Opsi <?= $label ?></label>
                    <input
                        type="text"
                        name="option_<?= $label ?>"
                        class="form-control"
                        value="<?= old('option_' . $label) ?>"
                        required>
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <label class="form-label">Jawaban Benar</label>
                <select name="correct_answer" class="form-select">
                    <?php foreach (['A', 'B', 'C', 'D', 'E'] as $label) : ?>
                        <option value="<?= $label ?>" <?= old('correct_answer') === $label ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Soal <small class="text-muted">(opsional, maks 2MB)</small></label>
                <input
                    type="file"
                    name="image"
                    accept="image/png, image/jpeg, image/jpg, image/webp"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
            <a href="<?= base_url($base . '/packages/' . $packageId . '/questions') ?>" class="btn btn-secondary">Batal</a>
        </form>

    </div>

</div>

<?= $this->endSection() ?>
