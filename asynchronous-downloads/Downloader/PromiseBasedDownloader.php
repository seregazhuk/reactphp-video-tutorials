<?php

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\Filesystem\Filesystem;
use React\Promise\PromiseInterface;
use React\Stream\WritableStreamInterface;

final class PromiseBasedDownloader
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

    public function download(string ...$urls): void
    {
        foreach ($urls as $url) {
            $this->openFileFor($url)->then(
                function (WritableStreamInterface $file) use ($url) {
                    $this->client->get($url)->then(
                        function (ResponseInterface $response) use ($file) {
                            $response->getBody()->pipe($file);
                        }
                    );
                }
            );
        }
    }

    private function openFileFor(string $url): PromiseInterface
    {
        $path = $this->directory . DIRECTORY_SEPARATOR . basename($url);

        return $this->filesystem->file($path)->open('cw');
    }
}
