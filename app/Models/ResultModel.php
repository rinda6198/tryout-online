<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
    protected $table         = 'results';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'exam_id', 'user_id', 'package_id', 'total_questions', 'correct_answers',
        'wrong_answers', 'score', 'is_passed',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /**
     * Get ranking for a particular package, ordered by score desc
     */
    public function getRanking(int $packageId)
    {
        return $this->select('results.*, users.name as user_name, users.username')
                     ->join('users', 'users.id = results.user_id')
                     ->where('results.package_id', $packageId)
                     ->orderBy('results.score', 'DESC')
                     ->orderBy('results.created_at', 'ASC')
                     ->findAll();
    }

    public function getUserRank(int $packageId, int $userId): array
    {
        $ranking = $this->getRanking($packageId);
        foreach ($ranking as $idx => $row) {
            if ((int) $row['user_id'] === $userId) {
                return ['rank' => $idx + 1, 'total' => count($ranking), 'data' => $row];
            }
        }
        return ['rank' => null, 'total' => count($ranking), 'data' => null];
    }

    public function getByUser(int $userId)
    {
        return $this->select('results.*, packages.title as package_title')
                     ->join('packages', 'packages.id = results.package_id')
                     ->where('results.user_id', $userId)
                     ->orderBy('results.id', 'DESC')
                     ->findAll();
    }

    public function getHighestScore(int $userId)
    {
        return $this->where('user_id', $userId)->orderBy('score', 'DESC')->first();
    }

    public function getByPackage(int $packageId)
    {
        return $this->select('results.*, users.name as user_name, users.username')
                     ->join('users', 'users.id = results.user_id')
                     ->where('results.package_id', $packageId)
                     ->orderBy('results.score', 'DESC')
                     ->findAll();
    }
}