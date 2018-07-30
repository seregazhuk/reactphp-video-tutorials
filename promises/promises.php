<?php

require 'vendor/autoload.php';

function http($url, $method) {
    $response = 'Data';
    $deferred = new \React\Promise\Deferred();

    if($response) {
        $deferred->resolve($response);
    } else {
        $deferred->reject(new Exception('No response'));
    }

    return $deferred->promise();
}

http('http://google.com', 'GET')
    ->then(function ($response) {
        throw new Exception('error');
        return strtoupper($response);
    })
    ->then(
        function ($response) {
            echo $response . PHP_EOL;
        })
    ->otherwise(
        function (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        });
