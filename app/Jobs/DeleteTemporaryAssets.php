<?php
/**
 * Created by PhpStorm.
 * Date: 22/01/19
 * Time: 18:55
 */


namespace App\Jobs;

use Illuminate\Support\Facades\Storage;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;

class DeleteTemporaryAssets extends Job
{
    public $folder;

    /**
     * Create a new job instance.
     *
     * @param $folder
     * @param $targetFile
     * @param $cloudFile
     */
    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        Storage::disk('local')->deleteDirectory($this->folder);
    }

}