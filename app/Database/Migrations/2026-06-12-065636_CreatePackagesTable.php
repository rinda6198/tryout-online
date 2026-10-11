<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'subject_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'duration' => ['type' => 'INT', 'constraint' => 11, 'comment' => 'duration in minutes'],
            'token' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'is_random_question' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_random_option' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'passing_grade' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'closed'], 'default' => 'draft'],
            'start_time' => ['type' => 'DATETIME', 'null' => true],
            'end_time' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('packages');
    }

    public function down()
    {
        $this->forge->dropTable('packages');
    }
}