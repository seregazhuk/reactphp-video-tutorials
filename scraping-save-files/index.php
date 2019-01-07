<?php

use Clue\React\Buzz\Browser;
use React\Filesystem\Filesystem;
use AsyncScraper\Downloader;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$client = new Browser($loop);
$downloader = new Downloader(
    $client->withOptions(['streaming' => true]),
    Filesystem::create($loop),
    __DIR__ . '/downloads'
);

$storage = new \AsyncScraper\Storage($loop, 'root:@localhost/scraping?idle=0');
$crawler = new \AsyncScraper\Crawler($client);
$scraper = new \AsyncScraper\Scraper($crawler, $downloader, $storage);

$urls = [
    'https://www.pexels.com/photo/adorable-animal-cat-cat-s-eyes-236603/',
    'https://www.pexels.com/photo/adorable-animal-baby-blur-177809/',
];

$scraper->scrape(...$urls);
$loop->run();
