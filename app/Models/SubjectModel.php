<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table         = 'subjects';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'code', 'description'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'code' => 'required|max_length[20]',
    ];
}