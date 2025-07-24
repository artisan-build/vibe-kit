<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ContinueCommand extends Command
{
    protected $signature = 'vibe:continue';

    protected $description = 'Tell the agent whether it is allowed to continue its work';

    public function handle(): int
    {
        if (File::exists(implode('/', [config('vibe.tasks_path'), '.paused']))) {
            $this->error('Work on this codebase is paused. Please do not continue with the current request.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
