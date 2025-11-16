<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run()
    {
        // TIDAK PERLU truncate jika sudah ada data
        // Level::truncate();

        // Update level 11-20 jadi premium
        for ($i = 11; $i <= 20; $i++) {
            Level::updateOrCreate(
                ['level_number' => $i],
                [
                    'level_name' => "Level $i",
                    'is_premium' => true,
                    'coin_price' => 80,
                    'reward_coins' => ($i == 20) ? 100 : 0,
                    'is_active' => true,
                ]
            );
        }

        // Update level 21-30 jadi premium
        for ($i = 21; $i <= 30; $i++) {
            Level::updateOrCreate(
                ['level_number' => $i],
                [
                    'level_name' => "Level $i",
                    'is_premium' => true,
                    'coin_price' => 80,
                    'reward_coins' => ($i == 30) ? 100 : 0,
                    'is_active' => true,
                ]
            );
        }

        // Jika mau buat level baru 31-40
        for ($i = 31; $i <= 40; $i++) {
            Level::updateOrCreate(
                ['level_number' => $i],
                [
                    'level_name' => "Level $i",
                    'is_premium' => true,
                    'coin_price' => 80,
                    'reward_coins' => ($i == 40) ? 100 : 0,
                    'is_active' => true,
                ]
            );
        }

        echo "Level premium berhasil disetup!\n";
    }
}