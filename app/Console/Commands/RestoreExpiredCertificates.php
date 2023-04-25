<?php

namespace App\Console\Commands;

use App\Models\VesselCertificate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RestoreExpiredCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:certificates-restore';

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
        VesselCertificate::where('is_relevant', false)
            ->where('date_valid', '<>', null)
            ->where('date_valid', '<=', Carbon::now())
            ->update(['is_archive' => false]);
        return 0;
    }
}
