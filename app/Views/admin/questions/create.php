<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">

<div class="card-header">
Tambah Soal
</div>

<div class="card-body">

<form method="post">

<?= csrf_field() ?>

<div class="mb-3">
<label>Soal</label>

<textarea
name="question_text"
class="form-control"
rows="5"
required></textarea>

</div>

<div class="mb-3">
<label>Poin</label>

<input
type="number"
name="points"
value="1"
class="form-control">

</div>

<hr>

<h6>Pilihan Jawaban</h6>

<input type="text" name="option_A" class="form-control mb-2" placeholder="Jawaban A" required>
<input type="text" name="option_B" class="form-control mb-2" placeholder="Jawaban B" required>
<input type="text" name="option_C" class="form-control mb-2" placeholder="Jawaban C" required>
<input type="text" name="option_D" class="form-control mb-2" placeholder="Jawaban D" required>
<input type="text" name="option_E" class="form-control mb-2" placeholder="Jawaban E" required>

<div class="mb-3">
<label>Kunci Jawaban</label>

<select
name="correct_answer"
class="form-select">

<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
<option value="E">E</option>

</select>
</div>

<button class="btn btn-primary">
Simpan Soal
</button>

<a href="<?= base_url('admin/packages/'.$packageId.'/questions') ?>"
class="btn btn-secondary">

Kembali </a>

</form>

</div>
</div>

<?= $this->endSection() ?>
