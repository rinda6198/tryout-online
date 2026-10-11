<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table         = 'questions';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['package_id', 'question_text', 'image', 'points'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $validationRules = [
        'package_id'    => 'required|integer',
        'question_text' => 'required|min_length[3]',
        'points'        => 'permit_empty|integer|greater_than[0]',
    ];

    public function getByPackage(int $packageId)
    {
        return $this->where('package_id', $packageId)->orderBy('id', 'ASC')->findAll();
    }

    public function getWithOptions(int $packageId)
    {
        $questions = $this->getByPackage($packageId);
        $optionModel = new OptionModel();

        foreach ($questions as &$q) {
            $q['options'] = $optionModel->where('question_id', $q['id'])->findAll();
        }

        return $questions;
    }
}