<?php

use Psr\Http\Message\ServerRequestInterface;

final class Logging
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        echo 'Method: ' . $request->getMethod() . PHP_EOL;
        echo 'Path: ' . $request->getUri()->getPath() . PHP_EOL;

        return $next($request);
    }
}
