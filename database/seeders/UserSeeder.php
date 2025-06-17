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


        User::create([
            'name' => 'hieu2',
            'email' => 'lhieu9254@gmail.com',
            'password' => Hash::make('pass'),
            'vai_tro_id' => 2,
            'hoat_dong' => 1
        ]);

        User::create([
            'name' => 'hieu3',
            'email' => 'hieultph49402@gmail.com',
            'password' => Hash::make('pass'),
            'vai_tro_id' => 3,
            'hoat_dong' => 1
        ]);

        User::create([
            'name' => 'hieu4',
            'email' => 'hieu123@gmail.com',
            'password' => Hash::make('pass'),
            'vai_tro_id' => 4,
            'hoat_dong' => 1
        ]);

        User::create([
            'name' => 'hieu5',
            'email' => 'hieu456@gmail.com',
            'password' => Hash::make('pass'),
            'vai_tro_id' => 5,
            'hoat_dong' => 1
        ]);
    }
}
