<?php

namespace App\Console\Commands;

use App\Models\CrewMatrix;
use Hamcrest\Text\IsEqualIgnoringWhiteSpaceTest;
use Illuminate\Console\Command;

class DeleteEmptyMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:delete-empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emptyMembers = CrewMatrix::doesntHave('changes')->doesntHave('plannedChanges')->get();
        foreach ($emptyMembers as $member) {
            $this->info($member->vessel->name . ', ' . $member->rank->name);
            $member->delete();
        }
        return Command::SUCCESS;
    }
}
