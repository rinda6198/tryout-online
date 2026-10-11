<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamDetailModel extends Model
{
    protected $table         = 'exam_details';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'exam_id', 'question_id', 'option_id', 'option_order', 'is_correct',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    public function getByExam(int $examId)
    {
        return $this->where('exam_id', $examId)->findAll();
    }

    public function getAnswer(int $examId, int $questionId)
    {
        return $this->where('exam_id', $examId)->where('question_id', $questionId)->first();
    }

    /**
     * Save / update a participant's answer (used by AJAX auto-save)
     */
    public function saveAnswer(int $examId, int $questionId, ?int $optionId, ?string $optionOrder = null)
    {
        $existing = $this->getAnswer($examId, $questionId);

        $data = [
            'exam_id'     => $examId,
            'question_id' => $questionId,
            'option_id'   => $optionId,
        ];

        if ($optionOrder !== null) {
            $data['option_order'] = $optionOrder;
        }

        if ($existing) {
            return $this->update($existing['id'], $data);
        }

        return $this->insert($data);
    }
}