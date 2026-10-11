<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PackageModel;
use App\Models\QuestionModel;
use App\Models\ResultModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel    = new UserModel();
        $packageModel = new PackageModel();
        $questionModel = new QuestionModel();
        $resultModel  = new ResultModel();

        $data = [
            'title'         => 'Dashboard Admin',
            'totalPeserta'  => count($userModel->getByRoleSlug('peserta')),
            'totalGuru'     => count($userModel->getByRoleSlug('guru')),
            'totalSoal'     => $questionModel->countAllResults(),
            'totalPaket'    => $packageModel->countAllResults(),
        ];

        // Chart data: average score per package (last 8 packages)
        $db = db_connect();
        $chart = $db->table('results')
            ->select('packages.title as package_title, AVG(results.score) as avg_score, COUNT(results.id) as participant_count')
            ->join('packages', 'packages.id = results.package_id')
            ->groupBy('results.package_id')
            ->orderBy('results.package_id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        $data['chartLabels'] = array_column($chart, 'package_title');
        $data['chartScores'] = array_map(fn($r) => round((float) $r['avg_score'], 2), $chart);
        $data['chartCounts'] = array_column($chart, 'participant_count');

        return view('admin/dashboard', $data);
    }
}