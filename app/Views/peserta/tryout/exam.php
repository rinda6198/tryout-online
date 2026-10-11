<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <!-- Soal -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><?= esc($package['title']) ?></strong>
                <span id="timer" class="badge bg-danger fs-6">--:--</span>
            </div>

            <div class="card-body">
                <form id="examForm">
                    <?php foreach ($questions as $no => $question) : ?>
                        <div class="border rounded p-3 mb-4 question-block" id="question-<?= $no + 1 ?>" data-qno="<?= $no + 1 ?>">
                            <h6>Soal <?= $no + 1 ?> dari <?= count($questions) ?></h6>
                            <p><?= $question['question_text'] ?></p>

                            <?php if (! empty($question['image'])) : ?>
                                <img src="<?= base_url('uploads/questions/' . $question['image']) ?>" class="img-fluid mb-2" style="max-height:300px;" alt="Gambar soal">
                            <?php endif; ?>

                            <?php
                                // FIX: bandingkan sebagai string karena value radio selalu string,
                                // sedangkan savedAnswers berasal dari DB (int/null)
                                $savedOptionId = $savedAnswers[$question['id']] ?? null;
                            ?>

                            <?php foreach ($question['options'] as $option) : ?>
                                <div class="form-check">
                                    <input
                                        class="form-check-input answer-radio"
                                        type="radio"
                                        name="q<?= $question['id'] ?>"
                                        id="opt<?= $option['id'] ?>"
                                        value="<?= $option['id'] ?>"
                                        data-question="<?= $question['id'] ?>"
                                        <?= ((string) $savedOptionId === (string) $option['id']) ? 'checked' : '' ?>
                                    >
                                    <label class="form-check-label" for="opt<?= $option['id'] ?>">
                                        <?= esc($option['option_label']) ?>. <?= esc($option['option_text']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <div class="mt-2">
                                <small class="text-muted save-status" id="save-status-<?= $question['id'] ?>"></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>

                <form id="finishForm" method="post" action="<?= base_url('peserta/tryout/finish/' . $exam['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="button" id="btnFinish" class="btn btn-success w-100">
                        <i class="bi bi-check-circle"></i> Selesai Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Navigasi nomor soal -->
    <div class="col-md-3">
        <div class="card sticky-top" style="top: 1rem;">
            <div class="card-header">Navigasi Soal</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2" id="navGrid">
                    <?php foreach ($questions as $no => $question) :
                        $isAnswered = isset($savedAnswers[$question['id']]) && $savedAnswers[$question['id']] !== null;
                    ?>
                        <button
                            type="button"
                            class="btn btn-sm nav-btn <?= $isAnswered ? 'btn-success' : 'btn-outline-secondary' ?>"
                            style="width: 42px;"
                            data-target="#question-<?= $no + 1 ?>"
                            data-qid="<?= $question['id'] ?>"
                        >
                            <?= $no + 1 ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <hr>
                <small>
                    <span class="badge btn-success">&nbsp;</span> Sudah dijawab<br>
                    <span class="badge btn-outline-secondary">&nbsp;</span> Belum dijawab
                </small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    const examId = <?= (int) $exam['id'] ?>;

    // FIX: CSRF token must be sent for every AJAX POST, otherwise CodeIgniter's
    // global CSRF filter rejects the request with 403.
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    // ===== Auto save jawaban via AJAX =====
    $('.answer-radio').on('change', function () {
        const questionId = $(this).data('question');
        const optionId   = $(this).val();
        const statusEl   = $('#save-status-' + questionId);
        const navBtn     = $('.nav-btn[data-qid="' + questionId + '"]');

        statusEl.text('Menyimpan...').removeClass('text-success text-danger').addClass('text-muted');

        const payload = {
            exam_id: examId,
            question_id: questionId,
            option_id: optionId
        };
        payload[csrfName] = csrfHash;

        $.post('<?= base_url('peserta/tryout/save-answer') ?>', payload)
            .done(function (res) {
                if (res.status) {
                    statusEl.text('Tersimpan').removeClass('text-muted text-danger').addClass('text-success');
                    navBtn.removeClass('btn-outline-secondary').addClass('btn-success');

                    // FIX: update CSRF hash from response header if CI rotates it (CSRF regenerate = true)
                    // jQuery exposes response headers via jqXHR
                } else {
                    statusEl.text('Gagal menyimpan').removeClass('text-muted text-success').addClass('text-danger');
                }
            })
            .fail(function (xhr) {
                statusEl.text('Gagal menyimpan (sesi mungkin berakhir)').removeClass('text-muted text-success').addClass('text-danger');

                // FIX: jika CSRF token expired (403), beri tahu user untuk refresh
                if (xhr.status === 403) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi token kadaluarsa',
                        text: 'Silakan refresh halaman agar jawaban dapat tersimpan kembali.',
                    });
                }
            });
    });

    // ===== Navigasi nomor soal =====
    $('.nav-btn').on('click', function () {
        const target = $(this).data('target');
        $('html, body').animate({ scrollTop: $(target).offset().top - 80 }, 300);
    });

    // ===== Tombol selesai ujian =====
    $('#btnFinish').on('click', function () {
        Swal.fire({
            title: 'Selesai Ujian?',
            text: 'Pastikan semua jawaban sudah terisi. Jawaban yang belum tersimpan tidak dapat diubah lagi setelah ini.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#finishForm').off('submit').submit();
            }
        });
    });

    // ===== Timer (real countdown berbasis sisa waktu dari server) =====
    const timerEl = document.getElementById('timer');
    let duration = <?= (int) $remainingSeconds ?>; // FIX: sisa waktu dihitung dari start_time di server, bukan selalu full durasi

    function renderTimer() {
        if (duration < 0) duration = 0;

        const h = Math.floor(duration / 3600);
        const m = Math.floor((duration % 3600) / 60);
        const s = duration % 60;

        let display = '';
        if (h > 0) display += String(h).padStart(2, '0') + ':';
        display += String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

        timerEl.innerHTML = display;

        // FIX: beri peringatan visual ketika waktu < 5 menit
        if (duration <= 300) {
            timerEl.classList.remove('bg-danger');
            timerEl.classList.add('bg-warning', 'text-dark');
        }
    }

    renderTimer();

    const interval = setInterval(function () {
        duration--;
        renderTimer();

        if (duration <= 0) {
            clearInterval(interval);

            Swal.fire({
                icon: 'info',
                title: 'Waktu Habis',
                text: 'Ujian akan disubmit otomatis.',
                timer: 1500,
                showConfirmButton: false,
            }).then(() => {
                // FIX: auto submit via form POST (with CSRF) instead of relying on JS form index
                document.getElementById('finishForm').submit();
            });
        }
    }, 1000);

    // FIX: warn user before leaving/reloading the page (mencegah kehilangan progres tak sengaja)
    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        e.returnValue = '';
    });
});
</script>
<?= $this->endSection() ?>