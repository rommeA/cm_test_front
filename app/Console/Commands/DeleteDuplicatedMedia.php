<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDuplicatedMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:deleteDuplicates';

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
        $duplicates = DB::table('media')
            ->select('file_name',
                'size',
                'model_id',
                'model_type',
                'collection_name',
                'mime_type',
                'deleted_at',
                DB::raw('COUNT(*)'))
            ->groupBy(['file_name',
                'size',
                'model_id',
                'model_type',
                'collection_name',
                'mime_type',
                'deleted_at'])
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $d_row) {
            $media = Media::where('file_name', $d_row->file_name)
                ->where('size', $d_row->size)
                ->where('model_id', $d_row->model_id)
                ->where('model_type', $d_row->model_type)
                ->where('collection_name', $d_row->collection_name)
                ->where('mime_type', $d_row->mime_type)
                ->get();

            if ($media->count() > 1) {
                $this->info($media[0]->delete());
            }
        }
        return Command::SUCCESS;
    }
}
