<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;

class RunCommand extends Command
{
    protected $signature = 'vibe:run';

    protected $description = 'This is the schedule runner';

    public function handle(): int
    {
        $running = implode('/', [config('vibe.tasks_path'), '.running']);

        if (File::exists($running)) {
            return self::SUCCESS;
        }

        File::put($running, Date::now()->toDateTimeString());

        // There is a currently active task
        // If all checklist items on the task are complete, mark the task complete and exit
        // Otherwise, fire off the next item on the checklist

        // There is not a currently active task, but there is one that is marked as queued
        // Start the queued task

        // There are no currently active tasks and no queued tasks
        // Check for GitHub issues tagged as ready for an agent

        // There are GitHub issues
        // Create a new task for each issue, mark them queued, and exit

        // There are no GitHub issues
        // Open the GitHub tech debt project and identify a new, unique opportunity for improvement. Add it as an item to the project.

        File::delete($running);

        return self::SUCCESS;
    }
}
