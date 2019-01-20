<?php

namespace AsyncScraper;

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

final class Crawler
{
    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function extractImageFromUrl($url): PromiseInterface
    {
        return $this->browser->get($url)->then(function (ResponseInterface $response) {
            return $this->extractFromHtml((string)$response->getBody());
        });
    }

    private function extractFromHtml(string $responseBody): Image
    {
        $crawler = new SymfonyCrawler($responseBody);
        $title = $crawler->filter('h1.box__title')->text();
        $tags = $crawler->filter('.list-inline.list-padding a')->extract(['_text']);
        $resolution = $crawler->filter('.icon-list .icon-list__title')->text();
        $link = $crawler->filter('.btn-primary.btn--lg.btn--splitted a');
        $rawSource = $link->attr('href');
        $source = substr($rawSource, 0, strpos($rawSource, '?'));
        $id = $link->attr('data-id');

        return new Image($id, $title, $resolution, $source, ...$tags);
    }
}
