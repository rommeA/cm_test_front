<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\VesselCertificate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ArchiveExpiredDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:archive-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive all not relevant vessel certificates and user documents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Document::where('is_relevant', false)
            ->where('date_valid', '<>', null)
            ->where('date_valid', '<=', Carbon::now())
            ->where('is_archive', false)
            ->update(['is_archive' => true]);

        VesselCertificate::where('is_relevant', false)
            ->where('date_valid', '<>', null)
            ->where('date_valid', '<=', Carbon::now())
            ->where('is_archive', false)
            ->update(['is_archive' => true]);

        return Command::SUCCESS;
    }
}
