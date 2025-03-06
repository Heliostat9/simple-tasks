<?php

namespace App\Jobs;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckUnassignedTasks implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Checking unassigned tasks...');

        $tasks = Task::query()->where('created_at', '<', Carbon::now()->subMinutes());

        $count = 0;

        $tasks->each(function (Task $task) use (&$count) {
           if (!$task->employees()->exists()) {
               $count++;
               $task->delete();
           }
        });

        Log::info("Deleted {$count} tasks at " . now());
    }
}
