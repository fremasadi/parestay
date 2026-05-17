<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan tiap jam agar pembayaran pending yang sudah lewat 24 jam cepat dibatalkan.
Schedule::command('booking:update-status')->hourly()->withoutOverlapping();
