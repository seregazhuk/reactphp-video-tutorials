<?php

use Clue\React\Buzz\Browser;
use React\Filesystem\Filesystem;
use AsyncScraper\Downloader;
use React\Socket\Connector;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$proxy = new Clue\React\Socks\Client('125.27.10.209:55448', new Connector($loop));

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
