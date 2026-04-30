<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Akun Admin
        User::factory()->create([
            'name' => 'Admin MoneyMate',
            'username' => 'admin',
            'email' => 'admin@moneymate.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // 1 Akun Biasa
        User::factory()->create([
            'name' => 'User Biasa',
            'username' => 'user',
            'email' => 'user@moneymate.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
