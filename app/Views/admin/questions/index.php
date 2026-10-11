<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h5><?= esc($package['title']) ?></h5>

```
<a href="<?= base_url('admin/packages/'.$package['id'].'/questions/create') ?>"
   class="btn btn-primary">
   Tambah Soal
</a>
```

</div>

<div class="card">
    <div class="card-body">

```
    <?php foreach($questions as $no => $question): ?>

        <div class="border rounded p-3 mb-3">

            <strong>
                Soal <?= $no+1 ?>
            </strong>

            <hr>

            <?= $question['question_text'] ?>

            <hr>

            <?php foreach($question['options'] as $opt): ?>

                <div>

                    <?= $opt['option_label'] ?>.

                    <?= esc($opt['option_text']) ?>

                    <?php if($opt['is_correct']): ?>

                        <span class="badge bg-success">
                            Kunci
                        </span>

                    <?php endif ?>

                </div>

            <?php endforeach ?>

            <div class="mt-3">

                <a href="<?= base_url('admin/questions/delete/'.$question['id']) ?>"
                   onclick="return confirm('Hapus soal?')"
                   class="btn btn-danger btn-sm">

                   Hapus
                </a>

            </div>

        </div>

    <?php endforeach ?>

</div>
```

</div>

<?= $this->endSection() ?>
