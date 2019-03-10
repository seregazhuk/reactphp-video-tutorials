<?php

namespace AsyncScraper;

use Exception;

final class Scraper
{
    private $crawler;

    private $downloader;

    private $storage;

    public function __construct(Crawler $crawler, Downloader $downloader, Storage $storage)
    {
        $this->crawler = $crawler;
        $this->downloader = $downloader;
        $this->storage = $storage;
    }

    public function scrape(string ...$urls): void
    {
        foreach ($urls as $url) {
            $this->crawler
                ->extractImageFromUrl($url)
                ->then(function (Image $image) {
                    $this->downloader->download($image->source);
                    return $image;
                })
                ->then(function (Image $image) {
                    $this->storage->saveIfNotExist($image);
                })
                ->otherwise(function (Exception $exception) {
                    echo $exception->getMessage() . PHP_EOL;
                });
        }
    }
}
