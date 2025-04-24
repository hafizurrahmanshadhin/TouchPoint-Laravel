<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command {
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Downgrade expired monthly/yearly subscriptions to free plan';

    public function handle() {
        $now = Carbon::now();

        // 1) find your free plan
        $freePlan = Plan::where('subscription_plan', 'free')->first();
        if (!$freePlan) {
            $this->error("No 'free' plan found in plans table.");
            return 1;
        }

        // 2) fetch all still‐active subscriptions that have actually expired
        $expired = UserSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No expired subscriptions to downgrade.');
            return 0;
        }

        // 3) loop & downgrade each
        foreach ($expired as $sub) {
            $sub->update([
                'plan_id'    => $freePlan->id,
                'starts_at'  => $sub->expires_at,
                'expires_at' => null,
                'status'     => 'active',
            ]);
            $this->info("User {$sub->user_id} downgraded to free plan (was {$sub->plan_id}).");
        }

        $this->info('All expired subscriptions have been downgraded.');
        return 0;
    }
}
