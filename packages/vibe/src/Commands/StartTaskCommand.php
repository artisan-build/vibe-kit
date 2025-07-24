<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use ArtisanBuild\Vibe\TaskStates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StartTaskCommand extends Command
{
    protected $signature = 'vibe:start-task {name}';

    protected $description = 'Start a new task';

    public function handle(): int
    {
        $tasks_file = implode('/', [config('vibe.tasks_path'), 'tasks.json']);
        $task_name = Str::slug($this->argument('name'));

        if (! file_exists($tasks_file)) {
            File::put($tasks_file, json_encode(['tasks' => [
                $task_name => [
                    'state' => TaskStates::InProgress->value,
                ],
            ], 'current' => $task_name]));

            return self::SUCCESS;
        }

        $tasks = json_decode(File::get($tasks_file), true);

        if (data_get($tasks, 'tasks.'.$task_name)) {
            $this->error("The task name {$task_name} has already been used. Please refer to the /tasks/tasks.json file to ensure that the name you are using is unique. **IMPORTANT** Do not change any of the task names in that file. Please just create a unique name for this task and run this command again.");

            return self::FAILURE;
        }

        $tasks['tasks'][$task_name] = ['state' => TaskStates::InProgress->value];
        $tasks['current'] = $task_name;

        File::put($tasks_file, json_encode($tasks));

        return self::SUCCESS;
    }
}
