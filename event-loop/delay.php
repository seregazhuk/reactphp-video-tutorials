<?php

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$loop->addTimer(1, function () {
    echo "After timer\n";
});

echo "Before timer\n";

$loop->run();
