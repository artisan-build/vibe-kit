<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;

class PauseCommand extends Command
{
    protected $signature = 'vibe:pause';

    protected $description = 'Pause agent work in the current codebase';

    public function handle(): int
    {
        File::put(implode('/', [config('vibe.tasks_path'), '.paused']), Date::now()->toDateTimeString());

        $this->info('Agent work in the current codebase is paused.');

        return self::SUCCESS;
    }
}
