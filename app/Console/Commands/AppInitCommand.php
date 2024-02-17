<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AppInitCommand extends Command
{
    protected $signature = 'app:init';

    protected $description = 'Run the app init commands';

    public function handle(): int
    {
        $this->clear();

        $this->migrate();

        if (app()->isProduction()) {
            $this->cache();
        }

        return CommandAlias::SUCCESS;
    }

    private function migrate(): void
    {
        $this->call('migrate', [
            '--force' => true,
        ]);

        $this->call('db:seed', [
            '--class' => 'RoleAndPermissionSeeder',
        ]);
    }

    private function clear(): void
    {
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->call('cache:clear');
    }

    private function cache(): void
    {
        $this->call('optimize:clear');
        $this->call('storage:link');
        $this->call('key:generate');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
    }
}
