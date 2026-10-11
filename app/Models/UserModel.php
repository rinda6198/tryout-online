<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'role_id', 'name', 'email', 'username', 'password', 'photo', 'is_active',
    ];
    protected $useTimestamps    = true;
    protected $returnType       = 'array';

    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email',
        'username' => 'required|min_length[3]|max_length[50]|alpha_numeric',
        'role_id'  => 'required|integer',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username sudah digunakan, silakan pilih yang lain.',
        ],
        'email' => [
            'is_unique' => 'Email sudah terdaftar.',
        ],
    ];

    /**
     * Get user joined with role info
     */
    public function getUserWithRole(int $id = null)
    {
        $builder = $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
                        ->join('roles', 'roles.id = users.role_id');

        if ($id !== null) {
            return $builder->find($id);
        }

        return $builder->findAll();
    }

    public function findByUsernameOrEmail(string $identity)
    {
        return $this->select('users.*, roles.slug as role_slug, roles.name as role_name')
                     ->join('roles', 'roles.id = users.role_id')
                     ->groupStart()
                        ->where('users.username', $identity)
                        ->orWhere('users.email', $identity)
                     ->groupEnd()
                     ->first();
    }

    public function getByRoleSlug(string $slug)
    {
        return $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
                     ->join('roles', 'roles.id = users.role_id')
                     ->where('roles.slug', $slug)
                     ->findAll();
    }
}