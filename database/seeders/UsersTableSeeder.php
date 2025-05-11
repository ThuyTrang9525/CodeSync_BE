<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'a@example.com',
                'password' => Hash::make('password1'),
                'role' => 'STUDENT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'b@example.com',
                'password' => Hash::make('password2'),
                'role' => 'STUDENT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'c@example.com',
                'password' => Hash::make('password3'),
                'role' => 'TEACHER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
