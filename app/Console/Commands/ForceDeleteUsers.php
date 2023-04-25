<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ForceDeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:flush-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes soft-deleted users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (User::onlyTrashed()->get() as $user) {
            $this->info($user->email . ' ' . $user->deleted_at);
            $user->forceDelete();
        }
        return Command::SUCCESS;
    }
}
