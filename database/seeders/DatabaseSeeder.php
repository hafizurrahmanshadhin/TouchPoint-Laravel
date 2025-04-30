<?php

namespace Database\Seeders;

use Database\Seeders\FirebaseTokenSeeder;
use Database\Seeders\NotificationsSeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\SystemSettingSeeder;
use Database\Seeders\TouchPointSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserSubscriptionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            FirebaseTokenSeeder::class,
            PlanSeeder::class,
            UserSubscriptionSeeder::class,
            SystemSettingSeeder::class,
            TouchPointSeeder::class,
            NotificationsSeeder::class,
        ]);
    }
}
