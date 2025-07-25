<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the database and set up for local development';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('migrate:fresh');
        // Do whatever seeding is required to get your database ready for local development

        return self::SUCCESS;
    }
}
