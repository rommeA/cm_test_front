<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\Media;
use App\Models\Seaman;
use App\Models\User;
use App\Models\VesselCertificate;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ForceDeleteMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:hardDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DELETES ALL soft deleted media';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $softDeletedMedia = Media::onlyTrashed()->get();
        foreach ($softDeletedMedia as $item) {
            $item->restore();
            $item->forceDelete();
        }

        foreach (Media::where('model_type', '\App\Model\User')->get() as $media) {
            if (! User::where('id', $media->model_id)->first())
            {
                $item->delete();
            }
        }
        return 0;
    }
}
