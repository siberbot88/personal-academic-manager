<?php

use App\Jobs\SendDailyDigestJob;
use App\Jobs\SendPreStudyReminderJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Week 8: Scheduled Email Reminders
Schedule::job(new SendDailyDigestJob())
    ->dailyAt(config('pam.schedules.daily_digest'));

Schedule::job(new SendPreStudyReminderJob())
    ->weekdays()
    ->at(config('pam.schedules.pre_study'));

