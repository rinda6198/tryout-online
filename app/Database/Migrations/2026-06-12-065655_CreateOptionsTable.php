<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'question_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'option_text' => ['type' => 'TEXT'],
            'is_correct' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'option_label' => ['type' => 'VARCHAR', 'constraint' => 1, 'comment' => 'A, B, C, D, E'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('question_id', 'questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('options');
    }

    public function down()
    {
        $this->forge->dropTable('options');
    }
}