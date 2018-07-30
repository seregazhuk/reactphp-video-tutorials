<?php

use Clue\React\Stdio\Stdio;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();
$input = new \React\Stream\ReadableResourceStream(STDIN, $loop);
$output = new \React\Stream\WritableResourceStream(STDOUT, $loop);

$connector = new Connector($loop);
$connector->connect('127.0.0.1:8000')
    ->then(
        function (ConnectionInterface $connection) use ($input, $output) {
            $input->pipe($connection)->pipe($output);
        },
        function (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    );

$loop->run();

