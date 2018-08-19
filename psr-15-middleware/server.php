<?php

require __DIR__ . '/vendor/autoload.php';

use FriendsOfReact\Http\Middleware\Psr15Adapter\GroupedPSR15Middleware;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

$loop = \React\EventLoop\Factory::create();

$server = new \React\Http\Server([
    (new GroupedPSR15Middleware($loop))
        ->withMiddleware(\Middlewares\ClientIp::class)
        ->withMiddleware(\Middlewares\Redirect::class, [['/secret' => '/']]),
    function (ServerRequestInterface $request, callable $next) {
        echo 'Client IP: ' . $request->getAttribute('client-ip') . PHP_EOL;
        return $next($request);
    },
    function () {
        return new Response(200, ['Content-Type' => 'text/plain'], 'Hello, world');
    }
]);
$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();
