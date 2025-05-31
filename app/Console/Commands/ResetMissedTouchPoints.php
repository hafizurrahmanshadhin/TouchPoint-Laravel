<?php

namespace App\Console\Commands;

use App\Models\TouchPoint;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetMissedTouchPoints extends Command {
    protected $signature   = 'touchpoints:reset-missed';
    protected $description = 'Reset user score and badge if they missed a touchpoint';

    public function handle(): int {
        $missedTouchPoints = TouchPoint::where('is_completed', false)
            ->whereDate('touch_point_start_date', '<', Carbon::today())
            ->get();

        foreach ($missedTouchPoints as $tp) {
            $user = $tp->user;
            if ($user) {
                $user->score = 0;
                $user->badge = 'bronze';
                $user->save();
            }
        }

        return Command::SUCCESS;
    }
}
