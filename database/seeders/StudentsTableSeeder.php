<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'userID' => 1, // Liên kết với userID từ bảng users
                'dateOfBirth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phoneNumber' => '0909123456',
                'avatarURL' => 'https://example.com/avatar1.jpg',
                'enrollmentDate' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
            ],
            [
                'userID' => 2, // Liên kết với userID từ bảng users
                'dateOfBirth' => '2001-11-22',
                'gender' => 'Female',
                'address' => '456 Trần Hưng Đạo, Quận 1, TP.HCM',
                'phoneNumber' => '0909988776',
                'avatarURL' => 'https://example.com/avatar2.jpg',
                'enrollmentDate' => '2023-01-15',
                'bio' => 'Thích đọc sách, vẽ tranh và học ngôn ngữ.',
            ],
        ]);
    }
}
