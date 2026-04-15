<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan setiap hari jam 00:05 — update status booking yang sudah selesai / kadaluarsa
Schedule::command('booking:update-status')->dailyAt('00:05');
