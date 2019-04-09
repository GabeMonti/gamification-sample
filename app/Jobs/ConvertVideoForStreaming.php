<?php

namespace App\Jobs;

use FFMpeg\Format\Video\X264;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;

class ConvertVideoForStreaming extends Job
{
    public $file;

    /**
     * Create a new job instance.
     *
     * @param $video
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create some video formats...
        $lowBitrateFormat = (new X264('aac', 'libx264'))->setKiloBitrate(env('FFMPEG_BITRARE', 1000));

            // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->file['disk'])
            ->open($this->file['folder'].$this->file['filename'])
            ->export()
            ->toDisk('local')
            ->inFormat($lowBitrateFormat)
            ->save($this->file['folder'].'converted/'.$this->file['filename']);

        dispatch(new UploadAssetJob(
            $this->file['folder'],
            $this->file['folder'].'converted/'.$this->file['filename'],
            $this->file['s3Path']
        ))->onQueue('high');


    }


}