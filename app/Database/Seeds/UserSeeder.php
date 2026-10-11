<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'role_id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@tryout.test',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 2,
                'name' => 'Budi Guru',
                'email' => 'guru@tryout.test',
                'username' => 'guru',
                'password' => password_hash('guru123', PASSWORD_DEFAULT),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 3,
                'name' => 'Andi Peserta',
                'email' => 'peserta@tryout.test',
                'username' => 'peserta',
                'password' => password_hash('peserta123', PASSWORD_DEFAULT),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}