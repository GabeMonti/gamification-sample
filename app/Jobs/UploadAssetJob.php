<?php
/**
 * Created by PhpStorm.
 * Date: 22/01/19
 * Time: 17:20
 */


namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class UploadAssetJob extends Job
{
    public $file;
    public $folder;
    public $cloudFile;

    /**
     * Create a new job instance.
     *
     * @param $folder
     * @param $targetFile
     * @param $cloudFile
     */
    public function __construct($folder, $targetFile, $cloudFile)
    {
        $this->folder = $folder;
        $this->file = $targetFile;
        $this->cloudFile = $cloudFile;
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
        Storage::disk('s3')->writeStream(
            $this->cloudFile,
            Storage::disk('local')->readStream($this->file)
        );

        dispatch(new DeleteTemporaryAssets(
            $this->file['folder']
        ))->onQueue('low');

    }
}