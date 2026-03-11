<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TaskService;

class CheckClientReviewDeadline extends Command
{
    protected $signature = 'check:client-review-deadline';

    protected $description = 'Auto complete expired client review tasks';

    public function handle()
    {
        $count = app(TaskService::class)->autoCompleteExpiredReviews();

        $this->info("Checked client reviews. {$count} task(s) auto-completed.");

        return 0;
    }
}