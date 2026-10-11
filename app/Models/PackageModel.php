<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table         = 'packages';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'subject_id', 'created_by', 'title', 'description', 'duration', 'token',
        'is_random_question', 'is_random_option', 'passing_grade', 'status',
        'start_time', 'end_time',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $validationRules = [
        'subject_id' => 'required|integer',
        'title'      => 'required|min_length[5]|max_length[150]',
        'duration'   => 'required|integer|greater_than[0]',
    ];

    public function getWithSubject(int $id = null)
    {
        $builder = $this->select('packages.*, subjects.name as subject_name, users.name as author_name')
                        ->join('subjects', 'subjects.id = packages.subject_id')
                        ->join('users', 'users.id = packages.created_by');

        if ($id !== null) {
            return $builder->find($id);
        }

        return $builder->orderBy('packages.id', 'DESC')->findAll();
    }

    public function getByCreator(int $userId)
    {
        return $this->select('packages.*, subjects.name as subject_name')
                     ->join('subjects', 'subjects.id = packages.subject_id')
                     ->where('packages.created_by', $userId)
                     ->orderBy('packages.id', 'DESC')
                     ->findAll();
    }

    public function getPublished()
    {
        return $this->select('packages.*, subjects.name as subject_name')
                     ->join('subjects', 'subjects.id = packages.subject_id')
                     ->where('packages.status', 'published')
                     ->orderBy('packages.id', 'DESC')
                     ->findAll();
    }

    public function getQuestionCount(int $packageId): int
    {
        return $this->db->table('questions')->where('package_id', $packageId)->countAllResults();
    }
}