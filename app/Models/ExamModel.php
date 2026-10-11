<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table         = 'exams';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id', 'package_id', 'question_order', 'start_time', 'end_time', 'status',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    /**
     * Find an ongoing exam for a user+package, if any
     */
    public function getOngoing(int $userId, int $packageId)
    {
        return $this->where('user_id', $userId)
                     ->where('package_id', $packageId)
                     ->where('status', 'ongoing')
                     ->first();
    }

    public function getHistory(int $userId)
    {
        return $this->select('exams.*, packages.title as package_title, results.score, results.is_passed')
                     ->join('packages', 'packages.id = exams.package_id')
                     ->join('results', 'results.exam_id = exams.id', 'left')
                     ->where('exams.user_id', $userId)
                     ->where('exams.status', 'finished')
                     ->orderBy('exams.id', 'DESC')
                     ->findAll();
    }
}