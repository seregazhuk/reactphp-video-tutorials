<?php

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();
$scraper = new \AsyncScraper\ScraperViaProxy($loop, __DIR__ . '/downloads', '127.0.0.1:9150');

$urls = [
    'https://www.pexels.com/photo/adorable-animal-cat-cat-s-eyes-236603/',
    'https://www.pexels.com/photo/adorable-animal-baby-blur-177809/',
];

$scraper->scrape(...$urls);
$loop->run();
