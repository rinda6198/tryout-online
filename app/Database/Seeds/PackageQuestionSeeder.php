<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PackageQuestionSeeder extends Seeder
{
    public function run()
    {
        // Sample package
        $this->db->table('packages')->insert([
            'subject_id' => 1,
            'created_by' => 2, // guru
            'title' => 'Try Out Matematika Dasar',
            'description' => 'Latihan soal matematika dasar untuk persiapan ujian',
            'duration' => 60,
            'token' => 'MTK123',
            'is_random_question' => 1,
            'is_random_option' => 1,
            'passing_grade' => 70,
            'status' => 'published',
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $packageId = $this->db->insertID();

        $questions = [
            [
                'text' => 'Berapakah hasil dari 12 + 8 x 2?',
                'options' => [
                    ['A', '20', 0],
                    ['B', '40', 0],
                    ['C', '28', 1],
                    ['D', '24', 0],
                ],
            ],
            [
                'text' => 'Akar kuadrat dari 144 adalah?',
                'options' => [
                    ['A', '10', 0],
                    ['B', '11', 0],
                    ['C', '12', 1],
                    ['D', '13', 0],
                ],
            ],
            [
                'text' => 'Sebuah persegi memiliki sisi 5 cm. Berapakah luasnya?',
                'options' => [
                    ['A', '10 cm2', 0],
                    ['B', '20 cm2', 0],
                    ['C', '25 cm2', 1],
                    ['D', '30 cm2', 0],
                ],
            ],
            [
                'text' => 'Hasil dari 7/8 + 1/8 adalah?',
                'options' => [
                    ['A', '1', 1],
                    ['B', '8/16', 0],
                    ['C', '7/16', 0],
                    ['D', '2', 0],
                ],
            ],
            [
                'text' => 'Jika x = 5 dan y = 3, maka nilai dari 2x + 3y adalah?',
                'options' => [
                    ['A', '16', 0],
                    ['B', '19', 1],
                    ['C', '21', 0],
                    ['D', '13', 0],
                ],
            ],
        ];

        foreach ($questions as $q) {
            $this->db->table('questions')->insert([
                'package_id' => $packageId,
                'question_text' => $q['text'],
                'points' => 20,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $questionId = $this->db->insertID();

            foreach ($q['options'] as $opt) {
                $this->db->table('options')->insert([
                    'question_id' => $questionId,
                    'option_label' => $opt[0],
                    'option_text' => $opt[1],
                    'is_correct' => $opt[2],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}