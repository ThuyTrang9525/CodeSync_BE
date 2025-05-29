<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class SendGoalReminder extends Command
{
    protected $signature = 'send:goal-reminder';
    protected $description = 'Send reminders for upcoming goals';

    public function handle()
    {
        $now = now();
        $soon = $now->copy()->addDay();

        $goals = \App\Models\Goal::where('status', '!=', 'completed')->whereBetween('deadline', [$now, $soon])->get();

        $this->info("Found " . $goals->count() . " goals that are due soon.");
        $this->info("Goals: " . $goals);
        
        foreach ($goals as $goal) {
            Notification::create([
                'receiverID' => $goal->userID,
                'senderID' => 2, // ví dụ: admin ID
                'content' => 'Bạn có mục tiêu "' . $goal->title . '" sắp đến hạn.',
                'type' => 'SYSTEM',
                'isRead' => 0,
                'createdAt' => now(),
                'classID' => null // TODO: set classID from $goal->classID
            ]);
        }

        // Log the number of reminders sent
        $this->info('✅ Goal reminders processed!');
    }
}
