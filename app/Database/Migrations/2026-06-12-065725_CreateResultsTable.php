<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResultsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'exam_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'package_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total_questions' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'correct_answers' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'wrong_answers' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'is_passed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('exam_id');
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('results');
    }

    public function down()
    {
        $this->forge->dropTable('results');
    }
}