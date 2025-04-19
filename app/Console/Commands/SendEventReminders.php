<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use App\Notifications\MobileEventReminder;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now    = now();
        $window = $now->copy()->addMinutes(30);  // configurable
        $events = Event::whereBetween('event_time', [$now, $window])->get();
    
        foreach ($events as $event) {
            $event->user->notify(new MobileEventReminder($event));
        }
    }
}
