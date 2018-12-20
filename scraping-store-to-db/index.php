<?php

require __DIR__ . '/vendor/autoload.php';


$loop = \React\EventLoop\Factory::create();
$browser = new \Clue\React\Buzz\Browser($loop);
$scraper = new \AsyncScraper\Scraper($browser);

$urls = [
    'https://www.pexels.com/photo/adorable-animal-cat-cat-s-eyes-236603/',
    'https://www.pexels.com/photo/adorable-animal-baby-blur-177809/',
];

$storage = new \AsyncScraper\Storage($loop, 'root:@localhost/scraping?idle=0');

$scraper->scrape(...$urls)
    ->then(function (array $images) use ($storage) {
        $storage->saveIfNotExist(...$images);
    });

$loop->run();
