<?php

namespace AsyncScraper;

use Exception;
use React\EventLoop\LoopInterface;
use React\MySQL\Factory;
use React\MySQL\QueryResult;
use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use function React\Promise\reject;
use React\Promise\RejectedPromise;
use function React\Promise\resolve;

final class Storage
{
    private $connection;

    public function __construct(LoopInterface $loop, string $uri)
    {
        $this->connection = (new Factory($loop))->createLazyConnection($uri);
    }

    public function saveIfNotExist(Image ...$images): void
    {
        foreach ($images as $image) {
            $this->isNotStored($image->id)
                ->then(function () use ($image) {
                    $this->save($image);
                });
        }
    }

    private function save(Image $image): void
    {
        $sql = 'INSERT INTO images (id, title, tags, resolution, source) VALUES (?, ?, ?, ?, ?)';
        $this->connection->query($sql, $image->toArray())
            ->then(function (QueryResult $result) {
                var_dump($result);
            }, function (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            });
    }

    private function isNotStored(int $id): PromiseInterface
    {
        $sql = 'SELECT 1 FROM images WHERE id = ?';
        return $this->connection->query($sql, [$id])
            ->then(
                function (QueryResult $result) {
                    return count($result->resultRows) ? reject() : resolve();
                },
                function (Exception $exception) {
                    echo 'Error: ' . $exception->getMessage() . PHP_EOL;
                }
            );
    }
}
