<?php

namespace AsyncScraper;

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use function React\Promise\all;
use React\Promise\PromiseInterface;
use Symfony\Component\DomCrawler\Crawler;

final class Scraper
{
    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function scrape(string ...$urls): PromiseInterface
    {
        $promises = array_map(function ($url) {
            return $this->extractFromUrl($url);
        }, $urls);

        return all($promises);
    }

    private function extract(string $responseBody): Image
    {
        $crawler = new Crawler($responseBody);
        $title = $crawler->filter('h1.box__title')->text();
        $tags = $crawler->filter('.list-inline.list-padding a')->extract(['_text']);
        $resolution = $crawler->filter('.icon-list .icon-list__title')->text();
        $link = $crawler->filter('.btn-primary.btn--lg.btn--splitted a');
        $source = $link->attr('href');
        $id = $link->attr('data-id');

        return new Image($id, $title, $resolution, $source, ...$tags);
    }

    private function extractFromUrl($url): PromiseInterface
    {
        return $this->browser->get($url)->then(function (ResponseInterface $response) {
            return $this->extract((string) $response->getBody());
        });
    }
}
