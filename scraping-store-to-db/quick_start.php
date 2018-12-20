<?php

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

require __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$browser = new Browser($loop);
$browser
    ->get('https://www.pexels.com/photo/adorable-animal-blur-cat-617278/')
    ->then(function (ResponseInterface $response) {
        $crawler = new Crawler((string) $response->getBody());
        // title - h1.box__title
        // tags - .list-inline.list-padding a
        // resolution - .icon-list .icon-list__title
        // source - .btn-primary.btn--lg.btn--splitted a
        $title = $crawler->filter('h1.box__title')->text();
        $tags = $crawler->filter('.list-inline.list-padding a')->extract(['_text']);
        $resolution = $crawler->filter('.icon-list .icon-list__title')->text();
        $link = $crawler->filter('.btn-primary.btn--lg.btn--splitted a');
        $source = $link->attr('href');
        $id = $link->attr('data-id');

        print_r([
            $title,
            $tags,
            $resolution,
            $source,
            $id
        ]);
    });

$loop->run();

