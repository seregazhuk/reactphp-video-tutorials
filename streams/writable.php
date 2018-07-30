<?php

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$duplex = new \React\Stream\DuplexResourceStream(STDIN, $loop, null, STDOUT)

$readable = new \React\Stream\ReadableResourceStream(STDIN, $loop);
$writable = new \React\Stream\WritableResourceStream(STDOUT, $loop);
$toUpper = new \React\Stream\ThroughStream(function ($chunk) {
    return strtoupper($chunk);
});

$readable->pipe($toUpper)->pipe($writable);

$loop->run();

