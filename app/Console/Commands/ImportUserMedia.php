<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\User;
use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportUserMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userMedia:import {--user_id=} {--office}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use to import scans for one user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->option('user_id');
        $user = User::where('id', $user_id)->first();
        if (! $user) {
            $this->error('User not found!');
            return Command::INVALID;
        }

        $baseDir = 'uploads/CrewPersonnel/Seaman/document/scan/';

        if ($this->option('office')) {
            $baseDir = 'uploads/OfficePersonnel/Employee/document/scan/';

        }

        $this->info($baseDir);

        $documents = Document::where('cm_id', '<>', null)->where('user_id', $user_id)->get();

        $corrupted = [];
        $counter = 0;
        foreach ($documents as $document) {
            $document_user = $document->user;
            if (! $document_user) {
                $document->delete();
                continue;
            }
            $path = $baseDir . $document->cm_id;
            try {
                $files = Storage::disk('public')->files($path);
                foreach ($files as $file) {
                    $ext =  strtolower(File::extension($file));

                    $filename = File::name($file);
                    $mime_type = File::mimeType(storage_path('app/public/' . $file));

                    $media = Media::where('name', $filename)
                        ->where('model_id', $document->id)
                        ->where('collection_name', 'scans')
                        ->where('mime_type', $mime_type)

                        ->first();

                    if ($media) {
                        unset($filename);
                        unset($media);
                        continue;
                    }

                    if ($ext == 'jpg' or $ext == 'jpeg' or $ext == 'pdf' or $ext == 'png') {
                        $num_page = 1;
                        if ($ext == 'pdf') {
                            $image = new Imagick(storage_path('app/public/' . $file));
                            $num_page = $image->getnumberimages();
                        }
                        unset($image);
                        $document
                            ->addMedia(storage_path('app/public/' . $file))
                            ->preservingOriginal()
                            ->withCustomProperties([
                                'is_pdf' => $ext == 'pdf',
                                'pages_count' => $num_page,
                            ])
                            ->toMediaCollection('scans');

                        $counter++;
                        $this->info(
                            $counter . '. ' .
                            $document->user->displayName . ', added scan for ' . $document->type?->name ?? 'undefined' .
                            ' (category ' . $document->category?->name ?? 'undefined' . ')'
                        );
                    }
                }
            } catch (\Exception $e) {
                $corrupted[] = $baseDir . "/" . $document->cm_id;
                $this->error($e->getMessage());
            }
        }
        return Command::SUCCESS;
    }
}
