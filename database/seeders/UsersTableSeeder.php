<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Danh sách người dùng mẫu
        $users = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'a@example.com',
                'password' =>Hash::make('123123'),
                'role' => 'STUDENT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'b@example.com',
                'password' => Hash::make('123123'),
                'role' => 'STUDENT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'c@example.com',
                'password' =>Hash::make('123123'),
                'role' => 'TEACHER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn D',
                'email' => 'd@example.com',
                'password' =>Hash::make('123123'),
                'role' => 'TEACHER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Văn E',
                'email' => 'e@example.com',
                'password' =>Hash::make('123123'),
                'role' => 'TEACHER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Hệ thống',
                'email' => 'admin@example.com',
                'password' =>Hash::make('123123'),
                'role' => 'ADMIN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Chèn vào bảng users
        DB::table('users')->insert($users);
    }
}
