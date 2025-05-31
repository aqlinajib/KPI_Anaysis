<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
        
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'koordinator',
                'email' => 'koordinator@gmail.com',
                'role' => 'superadm',
                'password' => Hash::make('koordinator123'),
            ],
            
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => Hash::make('user123'),
            ],

        ];

        foreach ($userData as $userData) {
            // Periksa apakah data sudah ada sebelum mencoba membuatnya
            if (!DB::table('users')->where('email', $userData['email'])->exists()) {
                DB::table('users')->insert($userData);
            }
        }
    }
}
