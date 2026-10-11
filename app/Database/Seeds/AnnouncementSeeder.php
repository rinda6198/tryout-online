<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('announcements')->insert([
            'title' => 'Selamat Datang di Try Out Online',
            'content' => 'Silakan ikuti try out yang tersedia sesuai jadwal. Pastikan koneksi internet stabil sebelum memulai ujian.',
            'created_by' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}