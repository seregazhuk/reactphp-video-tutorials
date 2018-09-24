<?php

require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

$loop = \React\EventLoop\Factory::create();
$tasks = [
    'go to the market',
];

$listTasks = function () use (&$tasks) {
    return new Response(200, ['Content-Type' => 'application/json'], json_encode($tasks));
};

$addNewTask = function (ServerRequestInterface $request) use (&$tasks) {
    $newTask = $request->getParsedBody()['task'] ?? null;
    if ($newTask) {
        $tasks[] = $newTask;
        return new Response(201);
    }

    return new Response(
        400, ['Content-Type' => 'application/json'], json_encode(['error' => 'task field is required'])
    );
};

$viewTask = function (ServerRequestInterface $request, $id) use (&$tasks) {
    return isset($tasks[$id])
        ? new Response(200, ['Content-Type' => 'application/json'], json_encode($tasks[$id]))
        : new Response(404);
};

$dispatcher = \FastRoute\simpleDispatcher(
    function (\FastRoute\RouteCollector $routes) use ($listTasks, $addNewTask, $viewTask) {
        $routes->get('/tasks/{id:\d+}', $viewTask);
        $routes->get('/tasks', $listTasks);
        $routes->post('/tasks', $addNewTask);
    }
);

$server = new \React\Http\Server(
    function (ServerRequestInterface $request) use ($dispatcher) {
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new Response(404);
            case \FastRoute\Dispatcher::FOUND:
                return $routeInfo[1]($request);
        }
    }
);

$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);

$server->listen($socket);
echo 'Listening on ' . str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL;
$loop->run();
