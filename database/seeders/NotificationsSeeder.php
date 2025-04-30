<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationsSeeder extends Seeder {
    public function run(): void {
        DB::table('notifications')->insert([
            [
                'id'              => '82ed2be9-4366-4ee5-a6bf-0ca63c1b983d',
                'type'            => 'App\\Notifications\\BadgeEarned',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => json_encode([
                    'title'   => 'Reward Earned',
                    'message' => 'You earned a silver Badge!',
                    'type'    => 'badge_earned',
                    'data'    => [
                        'badge' => 'silver',
                    ],
                ]),
                'read_at'         => null,
                'created_at'      => Carbon::parse('2025-04-30 05:24:36'),
                'updated_at'      => Carbon::parse('2025-04-30 05:24:36'),
            ],
            [
                'id'              => 'ffdd6338-ad77-4174-87b1-5d9efa56a8fd',
                'type'            => 'App\\Notifications\\TouchPointAdded',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => json_encode([
                    'title'   => 'Touchpoint Added',
                    'message' => 'Autumn Phillips added successfully',
                    'type'    => 'touch_point_added',
                    'data'    => [
                        'touch_point_id' => 9,
                    ],
                ]),
                'read_at'         => null,
                'created_at'      => Carbon::parse('2025-04-30 05:28:57'),
                'updated_at'      => Carbon::parse('2025-04-30 05:28:57'),
            ],
        ]);
    }
}
