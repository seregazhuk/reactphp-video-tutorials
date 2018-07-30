<?php

require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

$loop = \React\EventLoop\Factory::create();

$posts = [];
$server = new \React\Http\Server(function (ServerRequestInterface $request) use (&$posts) {
    $path = $request->getUri()->getPath();
    if ($path === '/store') {
        $posts[] = json_decode((string)$request->getBody(), true);

        return new Response(201);
    }

    return new Response(200, ['Content-Type' => 'application/json'], json_encode($posts));
});
$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();
