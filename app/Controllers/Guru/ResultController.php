<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\ResultModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\ExamDetailModel;
use App\Models\ExamModel;

class ResultController extends BaseController
{
    protected PackageModel $packageModel;
    protected ResultModel $resultModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->resultModel = new ResultModel();
    }

    private function findOwnedPackage($packageId)
    {
        $package = $this->packageModel->find($packageId);

        if (!$package) {
            return null;
        }

        // ADMIN boleh akses semua
        if (session()->get('role_slug') === 'admin') {
            return $package;
        }

        // GURU hanya paket miliknya
        if ((int) $package['created_by'] !== (int) session()->get('user_id')) {
            return null;
        }

        return $package;
    }

    public function index()
    {
        if (session()->get('role_slug') === 'admin') {

            $packages = $this->packageModel->getWithSubject();

            $title = 'Hasil Semua Paket Try Out';

        } else {

            $packages = $this->packageModel
                ->getByCreator(session()->get('user_id'));

            $title = 'Hasil Peserta';
        }

        foreach ($packages as &$pkg) {

            $pkg['participant_count'] = $this->resultModel
                ->where('package_id', $pkg['id'])
                ->countAllResults();

            $pkg['question_count'] = $this->packageModel
                ->getQuestionCount($pkg['id']);
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/results/index', [
            'title' => $title,
            'packages' => $packages,
            'base' => $base,
        ]);
    }

    public function byPackage($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()
                ->to('/guru/results')
                ->with('error', 'Paket tidak ditemukan.');
        }

        $results = $this->resultModel->getByPackage($packageId);

        $scores = array_map(
            fn($r) => (float) $r['score'],
            $results
        );

        $stats = [
            'total_peserta' => count($results),
            'avg_score' => count($scores)
                ? round(array_sum($scores) / count($scores), 2)
                : 0,
            'max_score' => count($scores)
                ? max($scores)
                : 0,
            'min_score' => count($scores)
                ? min($scores)
                : 0,
            'passed_count' => count(
                array_filter(
                    $results,
                    fn($r) => (int) $r['is_passed'] === 1
                )
            ),
        ];

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/results/by_package', [
            'title' => 'Hasil Peserta - ' . $package['title'],
            'package' => $package,
            'results' => $results,
            'stats' => $stats,
            'base' => $base,
        ]);
    }

    public function analysis($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()
                ->to('/guru/results')
                ->with('error', 'Paket tidak ditemukan.');
        }

        $questionModel = new QuestionModel();
        $optionModel = new OptionModel();
        $examDetailModel = new ExamDetailModel();
        $examModel = new ExamModel();

        $questions = $questionModel
            ->getByPackage($packageId);

        $totalFinishedExams = $examModel
            ->where('package_id', $packageId)
            ->where('status', 'finished')
            ->countAllResults();

        $analysis = [];

        foreach ($questions as $question) {

            $options = $optionModel
                ->getByQuestion($question['id']);

            $answers = $examDetailModel
                ->where('question_id', $question['id'])
                ->findAll();

            $totalAnswered = 0;
            $correctCount = 0;

            $distribution = [];

            foreach ($options as $opt) {

                $distribution[$opt['id']] = [
                    'label' => $opt['option_label'],
                    'text' => $opt['option_text'],
                    'is_correct' => (int) $opt['is_correct'],
                    'count' => 0,
                ];
            }

            foreach ($answers as $ans) {

                if (empty($ans['option_id'])) {
                    continue;
                }

                $totalAnswered++;

                if (isset($distribution[$ans['option_id']])) {

                    $distribution[$ans['option_id']]['count']++;

                    if (
                        $distribution[$ans['option_id']]['is_correct']
                    ) {
                        $correctCount++;
                    }
                }
            }

            $correctPercentage =
                $totalFinishedExams > 0
                ? round(
                    ($correctCount / $totalFinishedExams) * 100,
                    2
                )
                : 0;

            $difficulty = 'Belum Ada Data';

            if ($totalFinishedExams > 0) {

                if ($correctPercentage >= 70) {

                    $difficulty = 'Mudah';

                } elseif ($correctPercentage >= 30) {

                    $difficulty = 'Sedang';

                } else {

                    $difficulty = 'Sulit';
                }
            }

            $analysis[] = [
                'question' => $question,
                'distribution' => $distribution,
                'total_answered' => $totalAnswered,
                'correct_count' => $correctCount,
                'correct_percentage' => $correctPercentage,
                'difficulty' => $difficulty,
            ];
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/results/analysis', [
            'title' => 'Analisis Soal - ' . $package['title'],
            'package' => $package,
            'analysis' => $analysis,
            'totalFinishedExams' => $totalFinishedExams,
            'base' => $base,
        ]);
    }
}