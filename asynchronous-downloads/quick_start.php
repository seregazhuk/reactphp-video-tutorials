<?php

require __DIR__ . '/vendor/autoload.php';

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;

$loop = React\EventLoop\Factory::create();
$client = new Browser($loop);

$client->get('http://www.google.com/')->then(function (ResponseInterface $response) {
    var_dump($response->getHeaders(), (string)$response->getBody());
});

$loop->run();
