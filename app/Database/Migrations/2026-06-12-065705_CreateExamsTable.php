<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'package_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'question_order' => ['type' => 'TEXT', 'null' => true, 'comment' => 'JSON array of question ids order'],
            'start_time' => ['type' => 'DATETIME', 'null' => true],
            'end_time' => ['type' => 'DATETIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['ongoing', 'finished'], 'default' => 'ongoing'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exams');
    }

    public function down()
    {
        $this->forge->dropTable('exams');
    }
}