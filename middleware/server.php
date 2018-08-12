<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Logging.php';
require __DIR__ . '/CustomHeader.php';

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

$loop = \React\EventLoop\Factory::create();

$redirect = function (ServerRequestInterface $request, callable $next) {
    if ($request->getUri()->getPath() === '/admin') {
        return new Response(301, ['Location' => '/']);
    }

    return $next($request);
};

$hello = function () {
    return new Response(200, ['Content-Type' => 'text/plain'], 'Hello, world');
};
$server = new \React\Http\Server([
    new Logging(),
    new CustomHeader('X-Custom', 'foo'),
    $redirect,
    $hello
]);
$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();
