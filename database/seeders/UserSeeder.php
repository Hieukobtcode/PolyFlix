<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Polyflix',
            'email' => 'polyflixteam@gmail.com',
            'password' => Hash::make('pass'),
            'vai_tro_id' => 1,
            'hoat_dong' => 1
        ]);
    }
}