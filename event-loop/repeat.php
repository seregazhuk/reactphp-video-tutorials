<?php

use React\EventLoop\TimerInterface;

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$counter = 0;
$loop->addPeriodicTimer(1, function (TimerInterface $timer) use (&$counter, $loop) {
    $counter ++;

    if ($counter === 5) {
        $loop->cancelTimer($timer);
    }

    echo "Hello\n";
});

$loop->run();
