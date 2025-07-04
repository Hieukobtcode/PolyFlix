<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'polyflixteam@gmail.com'],
            [
                'name' => 'Polyflix',
                'email' => 'polyflixteam@gmail.com',
                'password' => Hash::make('nhấpass'),
                'vai_tro_id' => 1,
                'hoat_dong' => 1
            ]
        );

        User::updateOrCreate(
            ['email' => 'polyflixteam2@gmail.com'],
            [
                'name' => 'Polyflix2',
                'email' => 'polyflixteam2@gmail.com',
                'password' => Hash::make('pass'),
                'vai_tro_id' => 5,
                'hoat_dong' => 1
            ]
        );
    }
}
