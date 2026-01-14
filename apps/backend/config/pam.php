<?php

return [
    'notify_email' => env('NOTIFY_EMAIL'),

    'schedules' => [
        'daily_digest' => '07:00',
        'pre_study' => '19:45',
    ],

    'throttle' => [
        'event_hours' => 24,
    ],
];
