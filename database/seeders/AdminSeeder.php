<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        Admin::create([
            'name' => 'Admin',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('✅ Admin accounts created!');
        $this->command->info('Email: admin@gmail.com | Password: password');
        $this->command->info('Email: admin2@gmail.com | Password: password');
    }
}