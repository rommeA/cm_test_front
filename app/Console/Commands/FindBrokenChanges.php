<?php

namespace App\Console\Commands;

use App\Models\CrewMatrix;
use App\Models\Seaman;
use App\Models\SeamanChange;
use App\Models\SeamanPreviousService;
use Illuminate\Console\Command;

class FindBrokenChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changes:findBroken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List broken seamen changes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = [];
        foreach (CrewMatrix::all() as $member){
            $intersection_change = SeamanChange::where('member_id', $member->id)
                ->whereRelation('seamanInPs', 'date_to', '=', null)
                ->get();
            if ($intersection_change->count() > 1) {
                $ch = $intersection_change->first();
                $result[] = $ch->vessel?->name . ', rank: ' . $ch->member?->rankNameWithOrder . ', ' . $intersection_change->count();
            }
        }

        foreach ($result as $line) {
            $this->info($line);
        }
        return Command::SUCCESS;
    }
}
