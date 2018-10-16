<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Downloader/Downloader.php';

use Clue\React\Buzz\Browser;
use React\Filesystem\Filesystem;

$loop = React\EventLoop\Factory::create();
$client = new Browser($loop);
$downloader = new Downloader(
    $client->withOptions(['streaming' => true]),
    Filesystem::create($loop),
    __DIR__ . '/downloads'
);

$downloader->download(...[
    'http://www.sample-videos.com/video/mp4/720/big_buck_bunny_720p_1mb.mp4',
    'http://www.sample-videos.com/video/mp4/720/big_buck_bunny_720p_2mb.mp4',
    'http://www.sample-videos.com/video/mp4/720/big_buck_bunny_720p_5mb.mp4',
]);
$loop->run();
echo 'done';
