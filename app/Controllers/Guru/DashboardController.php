<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\QuestionModel;
use App\Models\ResultModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $packageModel  = new PackageModel();
        $questionModel = new QuestionModel();
        $resultModel   = new ResultModel();

        $userId = session()->get('user_id');

        // FIX: hanya hitung paket & soal milik guru yang login (bukan semua data)
        $myPackages = $packageModel->getByCreator($userId);

        $totalSoal = 0;
        $myPackageIds = [];
        foreach ($myPackages as $pkg) {
            $totalSoal += $packageModel->getQuestionCount($pkg['id']);
            $myPackageIds[] = $pkg['id'];
        }

        // Chart: rata-rata nilai per paket milik guru ini (max 8 terbaru)
        $db = db_connect();
        $chart = [];

        if (! empty($myPackageIds)) {
            $chart = $db->table('results')
                ->select('packages.title as package_title, AVG(results.score) as avg_score, COUNT(results.id) as participant_count')
                ->join('packages', 'packages.id = results.package_id')
                ->whereIn('results.package_id', $myPackageIds)
                ->groupBy('results.package_id')
                ->orderBy('results.package_id', 'DESC')
                ->limit(8)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'         => 'Dashboard Guru',
            'totalPaket'    => count($myPackages),
            'totalSoal'     => $totalSoal,
            'totalPublished' => count(array_filter($myPackages, fn($p) => $p['status'] === 'published')),
            'chartLabels'   => array_column($chart, 'package_title'),
            'chartScores'   => array_map(fn($r) => round((float) $r['avg_score'], 2), $chart),
            'chartCounts'   => array_column($chart, 'participant_count'),
        ];

        return view('guru/dashboard', $data);
    }
}
