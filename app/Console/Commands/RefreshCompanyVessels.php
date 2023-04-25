<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Models\Vessel;
use App\Models\VesselType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshCompanyVessels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vessels:refresh';

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
        $json_company_vessels = \File::get(storage_path() . '/database/CM/vessels_CM.json');
        $company_vessels = json_decode($json_company_vessels)->data;

        $this->info('importing company vessels');

        Vessel::where('is_external', false)->update(['is_external' => true]);

        foreach ($company_vessels as $vessel) {
            $vessel_info = DB::table('cm_vessels')->where(['cm_id' => $vessel->vessel_id])->first();
            $updated_vessel = Vessel::where('cm_id', $vessel_info->id)
                ->where('imo', $vessel->imo)->where('cm_vessel_id', $vessel->id)
                ->update(['is_external' => false]);
        }

        return Command::SUCCESS;
    }
}
