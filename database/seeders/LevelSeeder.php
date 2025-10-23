<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [];

        // Level 1-10 (Free)
        for ($i = 1; $i <= 10; $i++) {
            $levels[] = [
                'level_number' => $i,
                'level_name' => "Level $i",
                'is_premium' => false,
                'coin_price' => 0,
                'reward_coins' => $i == 10 ? 100 : 0, // Reward 100 coins setelah level 10
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Level 11-20 (Premium)
        for ($i = 11; $i <= 20; $i++) {
            $levels[] = [
                'level_number' => $i,
                'level_name' => "Level $i - Premium",
                'is_premium' => true,
                'coin_price' => 50, // Harga 50 coins per level premium
                'reward_coins' => $i == 20 ? 200 : 0, // Reward 200 coins setelah level 20
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Level::insert($levels);

        $this->command->info('✅ 20 Levels created (1-10 Free, 11-20 Premium)!');
    }
}