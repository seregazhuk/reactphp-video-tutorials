<?php

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$client = new Browser($loop);

$client->get('https://www.pexels.com/photo/adorable-animal-baby-blur-177809/')->then(
    function (ResponseInterface $response) {
        $crawler = new Crawler((string)$response->getBody());

        $title = $crawler->filter('h1.box__title')->text();
        $tags = $crawler->filter('.list-inline.list-padding li a')->extract(['_text']);
        $resolution = $crawler->filter('.icon-list .icon-list__title')->text();
        $source = $crawler->filter('.btn-primary.btn--lg.btn--splitted a')->attr('href');
        $id = $crawler->filter('.btn-primary.btn--lg.btn--splitted a')->attr('data-id');

        print_r([
            'id' => $id,
            'title' => $title,
            'tags' => $tags,
            'resolution' => $resolution,
            'source' => $source
        ]);
    }
);

$loop->run();
