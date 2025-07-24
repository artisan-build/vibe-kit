<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe;

use ArtisanBuild\Vibe\Commands\StartTaskCommand;
use ArtisanBuild\Vibe\Commands\TaskCommand;

class VibeServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            TaskCommand::class,
            StartTaskCommand::class,
        ]);
    }
}
