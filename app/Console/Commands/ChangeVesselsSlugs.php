<?php

namespace App\Console\Commands;

use App\Models\Vessel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeVesselsSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vessels:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs using IMO and MMSI of the vessel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Vessel::all() as $model) {
            $slug = 'i_' . Str::slug($model->imo, '_') . '_m_' . Str::slug($model->mmsi, '_');
            $count = DB::table('vessels')->where('slug', 'like', "$slug%")->count();
            if ($count) {
                $slug = $slug . "_" . $count;
            }
            $model->slug = $slug;
            $model->save();
        }
        return Command::SUCCESS;
    }
}
