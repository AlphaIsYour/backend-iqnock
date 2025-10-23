<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $levels = Level::all();

        // Dummy data tebakan gambar
        $dummyQuestions = [
            ['answer' => 'KUCING', 'points' => 10],
            ['answer' => 'ANJING', 'points' => 10],
            ['answer' => 'GAJAH', 'points' => 15],
            ['answer' => 'HARIMAU', 'points' => 15],
            ['answer' => 'SINGA', 'points' => 15],
            ['answer' => 'BURUNG', 'points' => 10],
            ['answer' => 'IKAN', 'points' => 10],
            ['answer' => 'KUDA', 'points' => 10],
            ['answer' => 'KAMBING', 'points' => 10],
            ['answer' => 'SAPI', 'points' => 10],
            ['answer' => 'AYAM', 'points' => 10],
            ['answer' => 'BEBEK', 'points' => 10],
            ['answer' => 'KELINCI', 'points' => 10],
            ['answer' => 'TIKUS', 'points' => 10],
            ['answer' => 'ULAR', 'points' => 15],
            ['answer' => 'BUAYA', 'points' => 15],
            ['answer' => 'MONYET', 'points' => 15],
            ['answer' => 'ZEBRA', 'points' => 15],
            ['answer' => 'JERAPAH', 'points' => 20],
            ['answer' => 'PANDA', 'points' => 20],
        ];

        foreach ($levels as $index => $level) {
            // Ambil data dummy berdasarkan index level
            $questionData = $dummyQuestions[$index % count($dummyQuestions)];
            
            Question::create([
                'level_id' => $level->id,
                'image_url' => 'https://via.placeholder.com/400x300.png?text=' . $questionData['answer'],
                'correct_answer' => $questionData['answer'],
                'points' => $questionData['points'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ ' . count($levels) . ' Questions created (1 per level with dummy images)!');
    }
}