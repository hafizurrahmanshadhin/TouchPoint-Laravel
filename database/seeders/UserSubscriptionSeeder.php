<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSubscriptionSeeder extends Seeder {
    public function run(): void {
        DB::table('user_subscriptions')->insert([
            [
                'id'         => 1,
                'user_id'    => 3,
                'plan_id'    => 1,
                'starts_at'  => '2025-04-27 06:34:25',
                'expires_at' => null,
                'status'     => 'active',
                'created_at' => '2025-04-27 06:34:25',
                'updated_at' => '2025-04-27 06:34:25',
                'deleted_at' => null,
            ],
        ]);
    }
}
