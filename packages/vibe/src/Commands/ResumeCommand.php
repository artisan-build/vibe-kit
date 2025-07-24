<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ResumeCommand extends Command
{
    protected $signature = 'vibe:resume';

    protected $description = 'Resume agent work in the current codebase';

    public function handle(): int
    {
        if (File::exists(implode('/', [config('vibe.tasks_path'), '.paused']))) {
            File::delete(implode('/', [config('vibe.tasks_path'), '.paused']));
        }
        $this->info('Resume agent work in the current codebase');

        return self::SUCCESS;
    }
}
