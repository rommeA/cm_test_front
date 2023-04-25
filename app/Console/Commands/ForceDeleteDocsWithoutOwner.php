<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;

class ForceDeleteDocsWithoutOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:delete';

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
        foreach (Document::all() as $document) {
            if (! $document->user) {
                $document->forceDelete();
            }
        }
    }
}
