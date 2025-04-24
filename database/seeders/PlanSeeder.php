<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder {
    public function run(): void {
        DB::table('plans')->insert([
            [
                'id'                => 1,
                'subscription_plan' => 'free',
                'price'             => 0.00,
                'billing_cycle'     => 'monthly',
                'touch_points'      => 15,
                'has_ads'           => true,
                'icon'              => false,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-04-23 10:15:59'),
                'updated_at'        => Carbon::parse('2025-04-23 10:15:59'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'subscription_plan' => 'monthly',
                'price'             => 4.99,
                'billing_cycle'     => 'monthly',
                'touch_points'      => null,
                'has_ads'           => false,
                'icon'              => true,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'updated_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'subscription_plan' => 'yearly',
                'price'             => 49.99,
                'billing_cycle'     => 'yearly',
                'touch_points'      => null,
                'has_ads'           => false,
                'icon'              => true,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'updated_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 4,
                'subscription_plan' => 'lifetime',
                'price'             => 89.99,
                'billing_cycle'     => 'lifetime',
                'touch_points'      => null,
                'has_ads'           => false,
                'icon'              => true,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'updated_at'        => Carbon::parse('2025-04-23 10:21:03'),
                'deleted_at'        => null,
            ],
        ]);
    }
}
