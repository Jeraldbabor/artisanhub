<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
    
        User::create([
            'name' => 'Buyer',
            'email' => 'buyer@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'buyer',
        ]);
    
        User::create([
            'name' => 'Artisan',
            'email' => 'artisan@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'artisan',
        ]);
    }
}
