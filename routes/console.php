<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\TaskService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function (TaskService $taskService) {
    $taskService->autoCompleteExpiredReviews();
})->daily();

Schedule::command('app:backup-database')->dailyAt('02:00');
