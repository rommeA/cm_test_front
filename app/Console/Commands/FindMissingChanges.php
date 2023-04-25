<?php

namespace App\Console\Commands;

use App\Models\CrewMatrix;
use App\Models\SeamanChange;
use App\Models\SeamanPreviousService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FindMissingChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:find-missing';

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
        $prevServcs = SeamanPreviousService::whereHas('vessel', function ($q) {
            $q->where('is_external', false);
        })->orderBy('date_from')->get();

        $count = 0;
        foreach ($prevServcs as $ps) {
            $changeIn = SeamanChange::where('seaman_in_ps_id', $ps->id)->first();
            if (! $changeIn) {
                $count++;
                $this->info('IN');
                $this->info('ps_id: ' . $ps->id . ', seaman in: ' . $ps->user_id . ', date: ' . $ps->date_from->format('d.m.Y'));

                $change = SeamanChange::select('seaman_changes.*')
                    ->where('seaman_changes.vessel_id', $ps->vessel_id)
                    ->where('seaman_changes.date', $ps->date_from)
                    ->where('seaman_changes.seaman_in_id', null)
                    ->where('seaman_changes.seaman_in_ps_id', null)
                    ->join('crew_matrices', 'crew_matrices.id', 'seaman_changes.member_id')
                    ->where('crew_matrices.seaman_rank_id', $ps->rank_id)
                    ->first();

                if ($change)
                {
                    $change->seaman_in_id = $ps->user_id;
                    $change->seaman_in_ps_id = $ps->id;
                    $change->save();
                } else {
                    $all_available_members = CrewMatrix::where('seaman_rank_id', $ps->rank_id)
                        ->where('vessel_id', $ps->vessel_id)
                        ->orderBy('order')
                        ->get();

                    $member = null;

                    foreach ($all_available_members as $mem) {
                        $query = SeamanChange::where('member_id', $mem->id)
                            ->where('seaman_in_id', '<>', $ps->seaman->id);

                        $ch = $query->whereDate('date', '<=', $ps->date_from)
                            ->whereDate('date_to_fact', '>', $ps->date_from)
                            ->first();

                        if ($ch) {
                            continue;
                        }

                        if (!$ch) {
                            $member = $mem;
                        }
                    }

                    if (! $member) {
                        $member = CrewMatrix::updateOrCreate([
                            'seaman_rank_id' => $ps->rank_id,
                            'vessel_id' => $ps->vessel_id,
                            'order' => count($all_available_members) + 1,
                        ]);
                    }

                    $change_by_date_from = SeamanChange::updateOrCreate([
                        'date' => $ps->date_from,
                        'date_to_plan' => $ps->date_from->addDays(90),
                        'date_to_fact' => $ps->date_to,
                        'vessel_id' => $ps->vessel_id,
                        'member_id' => $member->id,
                        'seaman_in_id' => $ps->user_id,
                        'seaman_in_ps_id' => $ps->id
                    ]);

                    if ($ps->date_to) {
                        $change_by_date_to = SeamanChange::updateOrCreate([
                            'date' => $ps->date_to,
                            'vessel_id' => $ps->vessel_id,
                            'member_id' => $member->id,
                            'seaman_out_id' => $ps->user_id,
                            'seaman_out_ps_id' => $ps->id,
                            'prev_change_id' => $change_by_date_from->id
                        ]);
                    }
                }

            }
        }
        $this->info($count);

        return Command::SUCCESS;
    }
}
