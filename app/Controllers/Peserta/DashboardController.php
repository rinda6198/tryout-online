<?php

namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\ResultModel;
use App\Models\ExamModel;
use App\Models\AnnouncementModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $packageModel = new PackageModel();
        $resultModel  = new ResultModel();
        $examModel    = new ExamModel();

        $userId = session()->get('user_id');

        $history = $resultModel->getByUser($userId);

        // FIX: hitung statistik sesuai spesifikasi dashboard peserta
        // - Jumlah try out diikuti
        // - Nilai tertinggi
        // - Ranking terakhir (dari try out yang paling baru diikuti)
        $highest = $resultModel->getHighestScore($userId);

        $lastRank = null;
        if (! empty($history)) {
            $latestResult = $history[0]; // getByUser sudah order by id DESC
            $rankInfo = $resultModel->getUserRank((int) $latestResult['package_id'], $userId);
            $lastRank = $rankInfo['rank'];
        }

         $announcementModel = new AnnouncementModel();


        return view('peserta/dashboard', [
            'title'        => 'Dashboard Peserta',
            'packages'     => $packageModel->getPublished(),
            'history'      => $history,
            'totalTryout'  => count($history),
            'highestScore' => $highest['score'] ?? null,
            'lastRank'     => $lastRank,
            'announcements' => $announcementModel->getActive(),
        ]);
    }
}