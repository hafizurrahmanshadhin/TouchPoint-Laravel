<?php

namespace App\Console\Commands;

use App\Models\TouchPoint;
use App\Notifications\TouchPointDue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDueTouchPointNotifications extends Command {
    protected $signature   = 'touchpoints:send-due-notifications';
    protected $description = 'Notify users about touchpoints due today or tomorrow';

    public function handle() {
        $today    = Carbon::today();
        $tomorrow = $today->copy()->addDay();

        TouchPoint::where('is_completed', false)
            ->whereBetween('touch_point_start_date', [$today, $tomorrow])
            ->with('user') // eager‐load user
            ->get()
            ->each(function (TouchPoint $tp) use ($today) {
                $user = $tp->user;
                if (!$user) {
                    return; // safety
                }

                // days until due: 0 = today, 1 = tomorrow
                $days = $tp->touch_point_start_date->diffInDays($today);

                // Check if we've already sent this exact reminder today
                $alreadySent = $user->notifications()
                    ->where('type', TouchPointDue::class)
                    ->where('data->touch_point_id', $tp->id)
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$alreadySent) {
                    // Send exactly once per day per touch‐point
                    $user->notify(new TouchPointDue($tp, $days));
                }
            });

        $this->info('Due‐soon notifications dispatched successfully.');
        return 0;
    }
}
