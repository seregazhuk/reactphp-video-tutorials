<?php

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$loop->addPeriodicTimer(1, function () {
    echo "Hello\n";
});

$loop->addTimer(1, function () {
    sleep(5);
});

$loop->run();
