<?php

namespace Sugihart\DeviceManager\Console;

use Illuminate\Console\Command;

class InstallDatabase extends Command
{
    protected $signature = 'sdm:install {--force : Run on production}';

    protected $description = 'Create missing device-manager tables on the separate sdm connection without replacing existing data';

    public function handle(): int
    {
        return $this->call('migrate', [
            '--database' => 'sdm',
            '--path' => realpath(__DIR__.'/../../database/migrations'),
            '--realpath' => true,
            '--force' => $this->option('force'),
        ]);
    }
}
