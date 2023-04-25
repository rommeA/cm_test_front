<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;

class ExpiredDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired-documents:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Notify HR-department about all expired user's documents";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
