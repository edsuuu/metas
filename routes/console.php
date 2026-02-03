<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Commands
|--------------------------------------------------------------------------
|
| Lembretes de ofensiva são enviados duas vezes ao dia:
| - 09:00: Lembrete matinal (incentivo para começar o dia)
| - 21:00: Lembrete noturno (urgência para não perder a ofensiva)
|
*/

Schedule::command('streaks:remind --period=morning')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('streaks:remind --period=evening')
    ->dailyAt('21:00')
    ->withoutOverlapping()
    ->onOneServer();
