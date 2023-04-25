<?php

namespace App\Console\Commands;

use App\Models\VesselCertificate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Imagick;

class ImportVesselCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vesselCertificate:import {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'importing certificate from CM by certificate ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $baseDir = 'uploads/Vessels/vesselCertificate';

        $document = VesselCertificate::where('cm_id', $this->argument('id'))->firstOrFail();

        $corrupted_path = '';

        try {
            $path = $baseDir . "/" . $this->argument('id');
            $files = Storage::disk('public')->files($path);
            foreach ($files as $file) {
                $ext = File::extension($file);
                $this->info($file);
                if ($ext == 'pdf' or $ext == 'png' or $ext == 'jpeg' or $ext == 'jpg') {
                    $image = new Imagick(storage_path('app/public/'.$file));
                    $num_page = $image->getnumberimages();
                    $document
                        ->addMedia(storage_path('app/public/'.$file))
                        ->preservingOriginal()
                        ->withCustomProperties([
                            'is_pdf' => $ext == 'pdf',
                            'pages_count' => $num_page,
                        ])
                        ->toMediaCollection('scans');
                    $this->info($document->vessel->name . ', added scan for ' . $document->type->name .
                        ' (category ' . $document->category->name . ')');
                } else {
                    $this->info('skipping file: ' . $file);
                }
            }
        } catch (\Exception $e) {
            $corrupted_path = $baseDir . "/" . $document->cm_id;
            $this->error($e->getMessage());
        }
        $this->warn('Corrupted paths: ' . $corrupted_path);
    }
}
