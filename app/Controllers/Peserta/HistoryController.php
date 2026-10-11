<?php

namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\ExamModel;
use App\Models\ResultModel;
use App\Models\PackageModel;

class HistoryController extends BaseController
{
    public function index()
    {
        $examModel = new ExamModel();

        return view('peserta/history/index', [
            'title'   => 'Riwayat Try Out',
            'history' => $examModel->getHistory(session()->get('user_id')),
        ]);
    }

    public function ranking($packageId)
    {
        $packageModel = new PackageModel();
        $resultModel  = new ResultModel();

        $package = $packageModel->find($packageId);

        // FIX: validasi paket ada, agar tidak error saat akses ranking/0 atau id tidak valid
        if (! $package) {
            return redirect()->to('/peserta/history')->with('error', 'Paket try out tidak ditemukan.');
        }

        // FIX: pastikan user hanya bisa melihat ranking paket yang pernah ia ikuti
        $userResult = $resultModel->where('user_id', session()->get('user_id'))
                                   ->where('package_id', $packageId)
                                   ->first();

        if (! $userResult) {
            return redirect()->to('/peserta/history')->with('error', 'Anda belum mengikuti try out ini.');
        }

        return view('peserta/history/ranking', [
            'title'      => 'Ranking - ' . $package['title'],
            'package'    => $package,
            'ranking'    => $resultModel->getRanking($packageId),
            'currentUserId' => session()->get('user_id'),
        ]);
    }
}