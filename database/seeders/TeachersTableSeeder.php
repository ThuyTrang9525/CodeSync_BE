<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teachers')->insert([
            [
                'userID' => 3,
                'dateOfBirth' => '2000-05-10',
                'gender' => 'Male',
                'address' => '123 Nguyễn Văn Cừ, Quận 5, TP.HCM',
                'phoneNumber' => '0909123456',
                'avatarURL' => 'https://example.com/avatar1.jpg',
                'enrollmentDate' => '2022-08-20',
                'bio' => 'Yêu thích lập trình và công nghệ.',
            ],  
        ]);
    }
}
