<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use ArtisanBuild\Vibe\TaskStates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TaskCommand extends Command
{
    protected $signature = 'vibe:task';

    protected $description = 'Get the name of the current task';

    public function handle(): int
    {
        $tasks_file = implode('/', [config('vibe.tasks_path'), 'tasks.json']);

        // No task file exists, so we must be working on a new task.
        if (! file_exists($tasks_file)) {
            File::put($tasks_file, json_encode(['tasks' => [], 'current' => '']));

            return self::SUCCESS;
        }

        $tasks = json_decode(File::get($tasks_file), true);

        $current_task = data_get($tasks, 'current');

        // The task file exists, but nothing has been marked as the current task, so we are starting a new task.
        if (! $current_task) {
            return self::SUCCESS;
        }

        // The current task has been completed, parked, or canceled so we are starting a new task.
        if (TaskStates::tryFrom(data_get($tasks, "{$current_task}.state"))?->ongoingTask()) {
            return self::SUCCESS;
        }

        // We are still working on an existing task.
        $this->line($current_task);

        return self::SUCCESS;
    }
}
