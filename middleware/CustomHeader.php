<?php

final class CustomHeader
{
    private $title;

    private $value;

    public function __construct($title, $value)
    {
        $this->title = $title;
        $this->value = $value;
    }

    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, callable $next)
    {
        /** @var \React\Http\Response $response */
        $response = $next($request);

        return $response->withHeader($this->title, $this->value);
    }
}
