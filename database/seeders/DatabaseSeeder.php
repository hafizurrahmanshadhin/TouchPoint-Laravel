<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SystemSettingSeeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            SystemSettingSeeder::class,
        ]);
    }
}
