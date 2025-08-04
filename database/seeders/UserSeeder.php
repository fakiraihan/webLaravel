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
        // Create regular user
        User::create([
            'username' => 'testuser',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
