<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Edit Soal - <?= esc($package['title']) ?></span>
      <a href="<?= base_url($base . '/packages/' . $package['id'] . '/questions') ?>"
   class="btn btn-secondary">
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
            action="<?= base_url($base . '/questions/update/' . $question['id']) ?>">

            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Pertanyaan</label>
                <textarea
                    name="question_text"
                    class="form-control"
                    rows="5"
                    required><?= old('question_text', $question['question_text']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Poin</label>
                <input
                    type="number"
                    name="points"
                    class="form-control"
                    min="1"
                    value="<?= old('points', $question['points']) ?>">
            </div>

            <hr>
            <h6>Pilihan Jawaban</h6>

            <?php
                // FIX: index options by label agar tidak bergantung pada urutan dari DB
                $optionsByLabel = [];
                foreach ($options as $opt) {
                    $optionsByLabel[$opt['option_label']] = $opt;
                }
            ?>

            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $label) : ?>
                <?php $opt = $optionsByLabel[$label] ?? null; ?>
                <div class="mb-3">
                    <label class="form-label">Opsi <?= $label ?></label>
                    <input
                        type="text"
                        class="form-control"
                        name="option_<?= $label ?>"
                        value="<?= old('option_' . $label, $opt['option_text'] ?? '') ?>"
                        required>
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <label class="form-label">Jawaban Benar</label>
                <select name="correct_answer" class="form-select">
                    <?php foreach (['A', 'B', 'C', 'D', 'E'] as $label) : ?>
                        <?php
                            $opt = $optionsByLabel[$label] ?? null;
                            $isSelected = old('correct_answer')
                                ? old('correct_answer') === $label
                                : (! empty($opt) && (int) $opt['is_correct'] === 1);
                        ?>
                        <option value="<?= $label ?>" <?= $isSelected ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (! empty($question['image'])) : ?>
                <div class="mb-3">
                    <label class="form-label d-block">Gambar Saat Ini</label>
                    <img
                        src="<?= base_url('uploads/questions/' . $question['image']) ?>"
                        style="max-width:300px"
                        class="img-thumbnail">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Ganti Gambar <small class="text-muted">(opsional, maks 2MB, kosongkan jika tidak diganti)</small></label>
                <input
                    type="file"
                    name="image"
                    accept="image/png, image/jpeg, image/jpg, image/webp"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update
            </button>

          <a href="<?= base_url($base . '/packages/' . $package['id'] . '/questions') ?>"
   class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

<?= $this->endSection() ?>
