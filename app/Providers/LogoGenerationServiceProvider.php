<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\LogoGeneration\MissionParserInterface;
use App\Services\LogoGeneration\MissionParser;
use Illuminate\Support\ServiceProvider;

class LogoGenerationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MissionParserInterface::class, MissionParser::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
