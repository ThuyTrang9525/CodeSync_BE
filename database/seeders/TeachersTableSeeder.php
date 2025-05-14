<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teachers')->insert([
            [
                'userID' => 3, // Liên kết với userID từ bảng users
                'dateOfBirth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phoneNumber' => '0909123456',
                'avatarURL' => 'https://example.com/avatar1.jpg',
                'enrollmentDate' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
            ],  
            [
                'userID' => 4, // Liên kết với userID từ bảng users
                'dateOfBirth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phoneNumber' => '0909123456',
                'avatarURL' => 'https://example.com/avatar2.jpg',
                'enrollmentDate' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
            ],  
            [
                'userID' => 5, // Liên kết với userID từ bảng users
                'dateOfBirth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phoneNumber' => '0909123456',
                'avatarURL' => 'https://example.com/avatar3.jpg',
                'enrollmentDate' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
            ]
        ]);
    }
}
