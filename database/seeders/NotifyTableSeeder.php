<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifyTableSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            [
                'receiverID' => 1,
                'content' => 'Chào mừng bạn đến với hệ thống!',
                'type' => 'SYSTEM',
                'isRead' => false,
                'createdAt' => Carbon::now(),
            ],
            [
                'receiverID' => 2,
                'content' => 'Bạn có bài kiểm tra nhóm sắp tới.',
                'type' => 'GROUP',
                'isRead' => false,
                'createdAt' => Carbon::now()->subDay(),
            ],
            [
                'receiverID' => 3,
                'content' => 'Một học sinh vừa tương tác với bạn.',
                'type' => 'INTERACTION',
                'isRead' => true,
                'createdAt' => Carbon::now()->subDays(2),
            ],
            [
                'receiverID' => 4,
                'content' => 'Bạn được chỉ định vào nhóm hướng dẫn.',
                'type' => 'GROUP',
                'isRead' => false,
                'createdAt' => Carbon::now()->subHours(5),
            ],
            [
                'receiverID' => 5,
                'content' => 'Có cập nhật mới từ hệ thống.',
                'type' => 'SYSTEM',
                'isRead' => true,
                'createdAt' => Carbon::now()->subMinutes(30),
            ],
            [
                'receiverID' => 6,
                'content' => 'Báo cáo hệ thống đã được gửi.',
                'type' => 'SYSTEM',
                'isRead' => false,
                'createdAt' => Carbon::now()->subDays(3),
            ],
            [
                'receiverID' => 7,
                'content' => 'Bạn nhận được phản hồi từ học sinh.',
                'type' => 'INTERACTION',
                'isRead' => false,
                'createdAt' => Carbon::now()->subHours(12),
            ],
        ];

        DB::table('notifications')->insert($notifications);
    }
}
