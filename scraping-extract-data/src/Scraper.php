<?php

namespace AsyncScraper;

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use function React\Promise\all;
use React\Promise\PromiseInterface;
use Symfony\Component\DomCrawler\Crawler;

final class Scraper
{
    private $client;

    public function __construct(Browser $client)
    {
        $this->client = $client;
    }

    public function scrape(string ...$urls): PromiseInterface
    {
        $promises = array_map(function(string $url) {
            return $this->parseUrl($url);
        }, $urls);

        return all($promises);
    }

    private function parseUrl(string $url): PromiseInterface
    {
        return $this->client->get($url)->then(function (ResponseInterface $response){
            return $this->extract((string) $response->getBody());
        });
    }

    private function extract(string $rawResponse)
    {
        $crawler = new Crawler($rawResponse);

        $title = $crawler->filter('h1.box__title')->text();
        $tags = $crawler->filter('.list-inline.list-padding li a')->extract(['_text']);
        $resolution = $crawler->filter('.icon-list .icon-list__title')->text();
        $source = $crawler->filter('.btn-primary.btn--lg.btn--splitted a')->attr('href');
        $id = $crawler->filter('.btn-primary.btn--lg.btn--splitted a')->attr('data-id');

        return new Image($id, $title, $resolution, $source, ...$tags);
    }
}
