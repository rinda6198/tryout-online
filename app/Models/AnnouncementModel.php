<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table         = 'announcements';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title', 'content', 'created_by', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $validationRules = [
        'title'   => 'required|min_length[3]|max_length[150]',
        'content' => 'required',
    ];

    public function getActive()
    {
        return $this->where('is_active', 1)->orderBy('id', 'DESC')->findAll();
    }
}