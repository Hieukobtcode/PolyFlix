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
            'name' => 'Polyflix Team',
            'email' => 'polyflixteam@gmail.com',
            'password' => Hash::make('password'),
            'vai_tro_id' => 1,
            'hoat_dong' => 1
        ]);
    }
}
