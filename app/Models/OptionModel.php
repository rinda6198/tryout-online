<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionModel extends Model
{
    protected $table         = 'options';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['question_id', 'option_text', 'is_correct', 'option_label'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $validationRules = [
        'question_id' => 'required|integer',
        'option_text' => 'required',
        'option_label' => 'required|max_length[1]',
    ];

    public function getByQuestion(int $questionId)
    {
        return $this->where('question_id', $questionId)->orderBy('option_label', 'ASC')->findAll();
    }

    public function getCorrectOption(int $questionId)
    {
        return $this->where('question_id', $questionId)->where('is_correct', 1)->first();
    }
}