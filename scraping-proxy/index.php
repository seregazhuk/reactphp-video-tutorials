<?php

use Clue\React\Buzz\Browser;
use React\Filesystem\Filesystem;
use AsyncScraper\Downloader;
use React\Socket\Connector;
use Clue\React\Socks\Client as SocksClient;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$proxy = new SocksClient('127.0.0.1:9150', new Connector($loop));
$browser = new Browser($loop, new Connector($loop, ['tcp' => $proxy]));

$downloader = new Downloader(
    $browser->withOptions(['streaming' => true]),
    Filesystem::create($loop),
    __DIR__ . '/downloads'
);

$storage = new \AsyncScraper\Storage($loop, 'root:@localhost/scraping?idle=0');
$crawler = new \AsyncScraper\Crawler($browser);
$scraper = new \AsyncScraper\Scraper($crawler, $downloader, $storage);

$urls = [
    'https://www.pexels.com/photo/adorable-animal-cat-cat-s-eyes-236603/',
    'https://www.pexels.com/photo/adorable-animal-baby-blur-177809/',
];

$scraper->scrape(...$urls);
$loop->run();
