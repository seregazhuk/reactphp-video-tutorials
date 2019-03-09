<?php

namespace AsyncScraper;

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\Filesystem\Filesystem;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Stream\WritableStreamInterface;
use function \React\Promise\Stream\UnwrapWritable;

final class Downloader
{
    private $client;

    private $filesystem;

    private $directory;

    public function __construct(Browser $client, Filesystem $filesystem, string $directory)
    {
        $this->client = $client;
        $this->filesystem = $filesystem;
        $this->directory = $directory;
    }

    public function download(string $url): PromiseInterface
    {
        $file = $this->openFileFor($url);
        $deferred = new Deferred();
        $file->on('close', function () use ($deferred) {
            $deferred->resolve();
        });

        $this->client->get($url)->then(
            function (ResponseInterface $response) use ($file) {
                $response->getBody()->pipe($file);
            }
        );

        return $deferred->promise();
    }

    private function openFileFor(string $url): WritableStreamInterface
    {
        $path = $this->directory . DIRECTORY_SEPARATOR . basename($url);

        return UnwrapWritable($this->filesystem->file($path)->open('cw'));
    }
}
