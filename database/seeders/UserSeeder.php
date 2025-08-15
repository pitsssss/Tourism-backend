<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
 
   public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // حتى ما ينعاد كل مرة
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // غيريها لكلمة سر أقوى
               'role' => 'super_admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

         User::updateOrCreate(
            ['email' => 'adminTrip@gmail.com'], // حتى ما ينعاد كل مرة
            [
                'name' => ' Admintrip',
                'password' => Hash::make('password1234'), // غيريها لكلمة سر أقوى
               'role' => 'admin_trips',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

          User::updateOrCreate(
            ['email' => 'tour@gmail.com'], // حتى ما ينعاد كل مرة
            [
                'name' => ' Admintour',
                'password' => Hash::make('12345678'), // غيريها لكلمة سر أقوى
               'role' => 'admin_tour_guides',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}

