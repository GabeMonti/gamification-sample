<?php

return [
    'default_disk' => 'local',
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', '/usr/bin/ffmpeg'),
        'threads' => env('FFMPEG_THREADS', 2),
    ],
    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', '/usr/bin/ffprobe'),
        'threads' => env('FFMPEG_THREADS', 2),
    ],
    'timeout' => env('FFMPEG_TIMEOUT', 3600),
];