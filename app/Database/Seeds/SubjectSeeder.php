<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Matematika', 'code' => 'MTK', 'description' => 'Mata pelajaran Matematika', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'description' => 'Mata pelajaran Bahasa Indonesia', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG', 'description' => 'Mata pelajaran Bahasa Inggris', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'IPA', 'code' => 'IPA', 'description' => 'Ilmu Pengetahuan Alam', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('subjects')->insertBatch($data);
    }
}