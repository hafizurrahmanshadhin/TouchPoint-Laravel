<?php

use App\Console\Commands\MakeDto;
use App\Console\Commands\MakeInterface;
use App\Console\Commands\MakeService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


