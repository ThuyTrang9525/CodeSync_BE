<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id' => 1,
                'name' => 'Nguyễn Văn A',
                'email' => 'a@example.com',
                'password' => Hash::make('password1'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Trần Thị B',
                'email' => 'b@example.com',
                'password' => Hash::make('password2'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Lê Văn C',
                'email' => 'c@example.com',
                'password' => Hash::make('password3'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
