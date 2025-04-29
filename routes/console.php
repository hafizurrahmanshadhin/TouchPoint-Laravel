<?php

use App\Console\Commands\ExpireSubscriptions;
use App\Console\Commands\MakeInterface;
use App\Console\Commands\MakeService;
use App\Console\Commands\SendDueTouchPointNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// registering make service class command
Artisan::command('make:service {name}', function ($name) {
    $this->call(MakeService::class, ['name' => $name]);
});

// registering make interface class command
Artisan::command('make:interface {name}', function ($name) {
    $this->call(MakeInterface::class, ['name' => $name]);
});

Schedule::command(ExpireSubscriptions::class)->daily();
Schedule::command(SendDueTouchPointNotifications::class)->dailyAt('08:00');

// Schedule::call(function () {
//     logger()->info('test it');
// })->everySecond();
