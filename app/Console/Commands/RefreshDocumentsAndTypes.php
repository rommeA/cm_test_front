<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshDocumentsAndTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:refresh-all-users-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete and re-import all user's documents categories, types, documents and it's scans";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Media::where('model_type', Document::class)->delete();
        Document::truncate();
        DocumentType::truncate();
        DocumentCategory::truncate();
        Artisan::call('db:seed --class=DocumentsTypesSeeder');
        Artisan::call('db:seed --class=EmployeesDocumentsSeeder');
        Artisan::call('db:seed --class=SeamanDocumentsSeeder');
        Artisan::call('db:seed --class=EmployeesDocumentsScansSeeder');
        Artisan::call('db:seed --class=EmployeesDocumentsScansSeeder');

        return Command::SUCCESS;
    }
}
