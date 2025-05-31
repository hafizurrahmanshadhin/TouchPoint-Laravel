<?php

use App\Console\Commands\ExpireSubscriptions;
use App\Console\Commands\MakeInterface;
use App\Console\Commands\MakeService;
use App\Console\Commands\ResetMissedTouchPoints;
use App\Console\Commands\SendDueTouchPointNotifications;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// registering make service class command
Artisan::command('make:service {name}', function ($name) {
    $this->call(MakeService::class, ['name' => $name]);
});

// registering make interface class command
Artisan::command('make:interface {name}', function ($name) {
    $this->call(MakeInterface::class, ['name' => $name]);
});

Schedule::command(ExpireSubscriptions::class)->daily();
Schedule::command(SendDueTouchPointNotifications::class)->dailyAt('06:00');
Schedule::command(ResetMissedTouchPoints::class)->daily();

// Schedule::call(function () {
//     logger()->info('test it');
// })->everySecond();
