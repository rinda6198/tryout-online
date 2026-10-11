<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'exam_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'question_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'option_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'option_order' => ['type' => 'TEXT', 'null' => true, 'comment' => 'JSON array of option ids order for randomized options'],
            'is_correct' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['exam_id', 'question_id']);
        $this->forge->addForeignKey('exam_id', 'exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('option_id', 'options', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('exam_details');
    }

    public function down()
    {
        $this->forge->dropTable('exam_details');
    }
}