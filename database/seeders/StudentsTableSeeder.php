<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('student')->insert([
            [
                'user_id' => 1,
                'date_of_birth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phone_number' => '0909123456',
                'avatar_url' => 'https://example.com/avatar1.jpg',
                'enrollment_date' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'date_of_birth' => '2001-11-22',
                'gender' => 'Female',
                'address' => '456 Trần Hưng Đạo, Quận 1, TP.HCM',
                'phone_number' => '0909988776',
                'avatar_url' => 'https://example.com/avatar2.jpg',
                'enrollment_date' => '2023-01-15',
                'bio' => 'Thích đọc sách, vẽ tranh và học ngôn ngữ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'date_of_birth' => '1999-03-05',
                'gender' => 'Male',
                'address' => '789 Lý Thường Kiệt, Quận 10, TP.HCM',
                'phone_number' => '0911223344',
                'avatar_url' => 'https://example.com/avatar3.jpg',
                'enrollment_date' => '2021-09-10',
                'bio' => 'Sinh viên ngành CNTT, thích backend.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
