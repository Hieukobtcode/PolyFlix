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
<<<<<<< HEAD
            'name' => 'PolyFlix Team',
            'email' => 'polyflixteam@gmail.com',
            'password' => Hash::make('pass'),
=======
            'name' => 'Polyflix',
            'email' => 'polyflixteam@gmail.com',
            'password' => Hash::make('123456'),
>>>>>>> 3e469c2528f1ce9ce312781d75f5e265e379b98a
            'vai_tro_id' => 1,
            'hoat_dong' => 1
        ]);

        User::create([
            'name' => 'hieu2',
            'email' => 'lhieu9254@gmail.com',
            'password' => Hash::make('123456'),
            'vai_tro_id' => 2,
            'hoat_dong' => 1
        ]);

        User::create([
            'name' => 'hieu3',
            'email' => 'hieultph49402@gmail.com',
            'password' => Hash::make('123456'),
            'vai_tro_id' => 1,
            'hoat_dong' => 1
        ]);
    }
}