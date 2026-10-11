<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('RoleSeeder');
        $this->call('UserSeeder');
        $this->call('SubjectSeeder');
        $this->call('PackageQuestionSeeder');
        $this->call('AnnouncementSeeder');
    }
}