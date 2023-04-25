<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportVessels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vessels:import';

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
        $this->createVesselTable();
        $this->createPreviousServiceTable();
        return 0;
    }

    public function createVesselTable()
    {
        Schema::dropIfExists('cm_vessels');

        Schema::create('cm_vessels', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('cm_id');
            $table->string('title');
            $table->integer('type_id');
            $table->string('flag');
            $table->string('crewing');
            $table->integer('deadweight');
            $table->integer('perfomance');
            $table->string('engineType');
            $table->boolean('experience');
            $table->string('imo')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        $json_all_vessels = \File::get(storage_path() . '/database/CM/all_vessels_CM.json');
        $all_vessels_raw = json_decode($json_all_vessels)->data;
        foreach ($all_vessels_raw as $item) {
            DB::table('cm_vessels')->insert([
                'cm_id' => $item->id,
                'title' => $item->title,
                'type_id' => $item->type_id,
                'flag' => $item->flag,
                'crewing' => $item->crewing,
                'deadweight' => $item->deadweight,
                'perfomance' => $item->perfomance,
                'engineType' => $item->engineType,
                'experience' => $item->experience,
                'imo' => $item->imo,
            ]);
        }
    }

    public function createPreviousServiceTable()
    {
        Schema::dropIfExists('cm_prev_service');

        Schema::create('cm_prev_service', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cm_id');
            $table->bigInteger('seaman_id');
            $table->bigInteger('vessel_id');
            $table->bigInteger('rank_id');
            $table->string('dateFrom');
            $table->string('dateTo')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        $json_prev_serv = \File::get(storage_path() . '/database/CM/seaman_prev_service_CM.json');
        $prev_serv_arr = json_decode($json_prev_serv)->data;

        foreach ($prev_serv_arr as $prev_serv) {
            DB::table('cm_prev_service')->insert([
                'cm_id' => $prev_serv->id,
                'seaman_id' => $prev_serv->seaman_id,
                'vessel_id' => $prev_serv->vessel_id,
                'rank_id' => $prev_serv->rank_id,
                'dateFrom' => $prev_serv->dateFrom,
                'dateTo' => $prev_serv->dateTo,
            ]);
        }
    }
}
