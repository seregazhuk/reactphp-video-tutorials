<?php

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$readable = new \React\Stream\ReadableResourceStream(STDIN, $loop, 1);

$readable->on('data', function ($chunk) use ($readable, $loop) {
    echo $chunk . PHP_EOL;
    $readable->pause();

    $loop->addTimer(1, function () use ($readable) {
        $readable->resume();
    });
});

$readable->on('end', function () {
    echo 'Finished' . PHP_EOL;
});

$loop->run();
