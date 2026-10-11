<?php

namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\ExamModel;
use App\Models\ExamDetailModel;
use App\Models\ResultModel;

class TryoutController extends BaseController
{
    public function index()
    {
        $packageModel = new PackageModel();

        return view('peserta/tryout/index', [
            'title'    => 'Daftar Try Out',
            'packages' => $packageModel->getPublished(),
        ]);
    }

    /**
     * Start (or resume) an exam attempt.
     * FIX: route is POST + CSRF protected, so no accidental re-trigger via GET links/crawlers.
     */
    public function start($packageId)
    {
        $packageModel = new PackageModel();
        $examModel    = new ExamModel();

        $package = $packageModel->find($packageId);

        if (! $package || $package['status'] !== 'published') {
            return redirect()->to('/peserta/tryout')->with('error', 'Paket try out tidak ditemukan atau belum dipublikasikan.');
        }

        // FIX: cek jadwal try out (start_time / end_time) jika diisi
        $now = date('Y-m-d H:i:s');
        
        if (! empty($package['start_time']) && $now < $package['start_time']) {
            return redirect()->to('/peserta/tryout')->with('error', 'Try out belum dimulai.');
        }
        if (! empty($package['end_time']) && $now > $package['end_time']) {
            return redirect()->to('/peserta/tryout')->with('error', 'Waktu try out telah berakhir.');
        }

        $userId = session()->get('user_id');

        // Resume jika masih ongoing
        $existing = $examModel->getOngoing($userId, $packageId);

        if ($existing) {
            return redirect()->to('/peserta/tryout/exam/' . $existing['id']);
        }

        // FIX: cek apakah peserta sudah pernah menyelesaikan paket ini (mencegah ulang try out tanpa izin)
        $resultModel = new ResultModel();
        $alreadyDone = $resultModel->where('user_id', $userId)
                                    ->where('package_id', $packageId)
                                    ->first();

        if ($alreadyDone) {
            return redirect()->to('/peserta/tryout')->with('error', 'Anda sudah menyelesaikan try out ini sebelumnya.');
        }

        $questionModel = new QuestionModel();
        $questions = $questionModel->getByPackage($packageId);

        if (empty($questions)) {
            return redirect()->to('/peserta/tryout')->with('error', 'Paket try out ini belum memiliki soal.');
        }

        // FIX: simpan urutan soal (untuk fitur random soal)
        $questionIds = array_column($questions, 'id');
        if ((int) $package['is_random_question'] === 1) {
            shuffle($questionIds);
        }

        $examId = $examModel->insert([
            'user_id'        => $userId,
            'package_id'     => $packageId,
            'question_order' => json_encode($questionIds),
            'start_time'     => date('Y-m-d H:i:s'),
            'status'         => 'ongoing',
        ]);

        return redirect()->to('/peserta/tryout/exam/' . $examId);
    }

    public function exam($examId)
    {
        $examModel      = new ExamModel();
        $questionModel  = new QuestionModel();
        $optionModel    = new OptionModel();
        $packageModel   = new PackageModel();
        $detailModel    = new ExamDetailModel();

        $exam = $examModel->find($examId);

        if (! $exam) {
            return redirect()->to('/peserta/tryout')->with('error', 'Data ujian tidak ditemukan.');
        }

        // FIX: pastikan exam ini milik peserta yang sedang login
        if ((int) $exam['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/peserta/tryout')->with('error', 'Anda tidak memiliki akses ke ujian ini.');
        }

        // FIX: jika sudah selesai, arahkan ke halaman hasil (mencegah buka ulang soal)
        if ($exam['status'] === 'finished') {
            return redirect()->to('/peserta/tryout/result/' . $exam['id']);
        }

        $package = $packageModel->find($exam['package_id']);

        if (! $package) {
            return redirect()->to('/peserta/tryout')->with('error', 'Paket tidak ditemukan.');
        }

        // FIX: ambil soal sesuai urutan yang sudah disimpan saat start() (untuk konsistensi random)
        $questionOrder = json_decode($exam['question_order'] ?? '[]', true);

        if (empty($questionOrder)) {
            $allQuestions  = $questionModel->getByPackage($exam['package_id']);
            $questionOrder = array_column($allQuestions, 'id');
        }

        $questions = [];
        foreach ($questionOrder as $qid) {
            $q = $questionModel->find($qid);
            if (! $q) {
                continue;
            }

            $options = $optionModel->getByQuestion($qid);

            // FIX: acak urutan opsi jika diaktifkan, tapi konsisten per soal (tidak berubah setiap reload)
            if ((int) $package['is_random_option'] === 1) {
                // gunakan seed berbasis exam_id + question_id supaya urutan tetap sama saat reload
                mt_srand((int) $exam['id'] * 1000 + (int) $qid);
                shuffle($options);
                mt_srand(); // reset seed
            }

            $q['options'] = $options;
            $questions[]  = $q;
        }

        if (empty($questions)) {
            return redirect()->to('/peserta/tryout')->with('error', 'Belum ada soal pada paket ini.');
        }

        // FIX: ambil jawaban yang sudah tersimpan sebelumnya (untuk ditampilkan kembali jika reload)
        $savedAnswers = [];
        foreach ($detailModel->getByExam($exam['id']) as $detail) {
            $savedAnswers[$detail['question_id']] = $detail['option_id'];
        }

        // FIX: hitung sisa waktu berdasarkan start_time, bukan selalu mulai dari durasi penuh
        $elapsedSeconds  = time() - strtotime($exam['start_time']);
        $totalSeconds    = (int) $package['duration'] * 60;
        $remainingSeconds = max($totalSeconds - $elapsedSeconds, 0);

        // FIX: jika ternyata waktu sudah habis (misal user menutup tab lama), langsung auto-finish
        if ($remainingSeconds <= 0) {
            return redirect()->to('/peserta/tryout/exam-auto-finish/' . $exam['id']);
        }

        return view('peserta/tryout/exam', [
            'title'            => 'Kerjakan Try Out',
            'exam'             => $exam,
            'package'          => $package,
            'questions'        => $questions,
            'savedAnswers'     => $savedAnswers,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    /**
     * AJAX: simpan jawaban otomatis.
     * FIX: validasi exam_id milik user, validasi question_id ada di paket exam,
     *      handle option_id kosong (radio "batal pilih" -> null), CSRF aman karena
     *      front-end mengirim csrf token via header (lihat exam.php).
     */
    public function saveAnswer()
    {
        // Hanya menerima request AJAX/POST
        if (! $this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON(['status' => false, 'message' => 'Method not allowed']);
        }

        $examId     = (int) $this->request->getPost('exam_id');
        $questionId = (int) $this->request->getPost('question_id');
        $optionId   = $this->request->getPost('option_id');

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);

        // FIX: validasi exam ada, milik user yang login, dan masih ongoing
        if (! $exam) {
            return $this->response->setStatusCode(404)->setJSON(['status' => false, 'message' => 'Ujian tidak ditemukan.']);
        }

        if ((int) $exam['user_id'] !== (int) session()->get('user_id')) {
            return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        if ($exam['status'] !== 'ongoing') {
            return $this->response->setStatusCode(409)->setJSON(['status' => false, 'message' => 'Ujian sudah berakhir.']);
        }

        // FIX: validasi question_id memang bagian dari paket exam ini
        $questionModel = new QuestionModel();
        $question = $questionModel->find($questionId);

        if (! $question || (int) $question['package_id'] !== (int) $exam['package_id']) {
            return $this->response->setStatusCode(422)->setJSON(['status' => false, 'message' => 'Soal tidak valid.']);
        }

        // FIX: jika option_id kosong/0/"" -> simpan sebagai null (jawaban dikosongkan)
        $optionId = (! empty($optionId)) ? (int) $optionId : null;

        // FIX: validasi option_id memang milik question ini (jika diisi)
        if ($optionId !== null) {
            $optionModel = new OptionModel();
            $option = $optionModel->find($optionId);

            if (! $option || (int) $option['question_id'] !== $questionId) {
                return $this->response->setStatusCode(422)->setJSON(['status' => false, 'message' => 'Pilihan jawaban tidak valid.']);
            }
        }

        $detailModel = new ExamDetailModel();
        $detailModel->saveAnswer($examId, $questionId, $optionId);

        return $this->response->setJSON(['status' => true]);
    }

    /**
     * Finish exam manually (button) or via auto-submit.
     * FIX: cek ownership, cek status (mencegah double insert -> duplicate key error pada results.exam_id),
     *      gunakan package_id dari exam (bukan dari request), dan hitung skor berbobot (points) bukan hanya jumlah benar.
     */
    public function finish($examId)
    {
        $examModel     = new ExamModel();
        $detailModel   = new ExamDetailModel();
        $optionModel   = new OptionModel();
        $resultModel   = new ResultModel();
        $questionModel = new QuestionModel();
        $packageModel  = new PackageModel();

        $exam = $examModel->find($examId);

        if (! $exam) {
            return redirect()->to('/peserta/tryout')->with('error', 'Data ujian tidak ditemukan.');
        }

        // FIX: validasi kepemilikan
        if ((int) $exam['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/peserta/tryout')->with('error', 'Anda tidak memiliki akses ke ujian ini.');
        }

        // FIX: jika sudah finished sebelumnya, jangan insert result lagi (hindari error unique constraint)
        if ($exam['status'] === 'finished') {
            return redirect()->to('/peserta/tryout/result/' . $examId);
        }

        $questions = $questionModel->getByPackage($exam['package_id']);

        $correct     = 0;
        $wrong       = 0;
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $points = (int) ($question['points'] ?? 1);
            $totalPoints += $points;

            $answer = $detailModel->getAnswer($examId, $question['id']);

            if (! $answer || empty($answer['option_id'])) {
                $wrong++;
                continue;
            }

            $correctOption = $optionModel->getCorrectOption($question['id']);

            // FIX: handle kasus soal tanpa kunci jawaban (correctOption null) -> dianggap salah, bukan error
            if ($correctOption && (int) $answer['option_id'] === (int) $correctOption['id']) {
                $correct++;
                $earnedPoints += $points;

                // FIX: simpan status benar/salah ke exam_details untuk analisis soal
                $detailModel->update($answer['id'], ['is_correct' => 1]);
            } else {
                $wrong++;
                $detailModel->update($answer['id'], ['is_correct' => 0]);
            }
        }

        $total = count($questions);

        // FIX: skor berbasis bobot poin (lebih akurat daripada (benar/total)*100 jika poin per soal berbeda)
        $score = $totalPoints > 0
            ? round(($earnedPoints / $totalPoints) * 100, 2)
            : 0;

        $package = $packageModel->find($exam['package_id']);

        $resultModel->insert([
            'exam_id'         => $examId,
            'user_id'         => $exam['user_id'],
            'package_id'      => $exam['package_id'],
            'total_questions' => $total,
            'correct_answers' => $correct,
            'wrong_answers'   => $wrong,
            'score'           => $score,
            'is_passed'       => $score >= (float) $package['passing_grade'] ? 1 : 0,
        ]);

        $examModel->update($examId, [
            'status'   => 'finished',
            'end_time' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/peserta/tryout/result/' . $examId);
    }

    /**
     * FIX: route tambahan untuk auto-finish ketika exam() mendeteksi waktu sudah habis
     * (dipanggil via redirect dari exam(), method GET sehingga aman tanpa CSRF body).
     */
    public function autoFinish($examId)
    {
        $examModel = new ExamModel();
        $exam = $examModel->find($examId);

        if (! $exam || (int) $exam['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/peserta/tryout')->with('error', 'Akses ditolak.');
        }

        if ($exam['status'] === 'finished') {
            return redirect()->to('/peserta/tryout/result/' . $examId);
        }

        return $this->finish($examId);
    }

    public function result($examId)
    {
        $resultModel = new ResultModel();
        $examModel   = new ExamModel();

        $exam = $examModel->find($examId);

        if (! $exam) {
            return redirect()->to('/peserta/dashboard')->with('error', 'Data ujian tidak ditemukan.');
        }

        // FIX: validasi kepemilikan agar peserta tidak bisa melihat hasil peserta lain
        if ((int) $exam['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/peserta/dashboard')->with('error', 'Anda tidak memiliki akses ke hasil ujian ini.');
        }

        $result = $resultModel->where('exam_id', $examId)->first();

        // FIX: jika belum ada result (misal exam masih ongoing / data hilang), arahkan ke exam
        if (! $result) {
            if ($exam['status'] === 'ongoing') {
                return redirect()->to('/peserta/tryout/exam/' . $examId);
            }
            return redirect()->to('/peserta/dashboard')->with('error', 'Hasil ujian belum tersedia.');
        }

        // FIX: tambahkan info ranking peserta untuk paket ini agar bisa ditampilkan di halaman hasil
        $rankInfo = $resultModel->getUserRank($exam['package_id'], $exam['user_id']);

        return view('peserta/tryout/result', [
            'title'    => 'Hasil Try Out',
            'result'   => $result,
            'rankInfo' => $rankInfo,
        ]);
    }
}