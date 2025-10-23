<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            LevelSeeder::class,
            QuestionSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}