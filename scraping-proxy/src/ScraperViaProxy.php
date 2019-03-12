<?php

namespace AsyncScraper;

use Clue\React\Buzz\Browser;
use Clue\React\Socks\Client as SocksClient;
use React\EventLoop\LoopInterface;
use React\Filesystem\Filesystem;
use React\Socket\Connector;

final class ScraperViaProxy
{
    /**
     * @var LoopInterface
     */
    private $loop;
    /**
     * @var string
     */
    private $directory;
    /**
     * @var string[]
     */
    private $proxies;

    public function __construct(LoopInterface $loop, string $directory, string ...$proxies)
    {
        $this->loop = $loop;
        $this->directory = $directory;
        $this->proxies = $proxies;
    }

    public function scrape(string ...$urls): void
    {
        foreach ($urls as $url) {
            $this->scraperForRandomProxy()->scrape($url);
        }
    }

    private function scraperForRandomProxy(): Scraper
    {
        $client = new Browser($this->loop, new Connector($this->loop, ['tcp' => $this->randomProxyClient()]));
        $downloader = new Downloader(
            $client->withOptions(['streaming' => true]),
            Filesystem::create($this->loop),
            $this->directory
        );

        $storage = new \AsyncScraper\Storage($this->loop, 'root:@localhost/scraping?idle=0');
        $crawler = new \AsyncScraper\Crawler($client);
        return new \AsyncScraper\Scraper($crawler, $downloader, $storage);
    }

    private function randomProxyClient(): SocksClient
    {
        $server = $this->proxies[array_rand($this->proxies)];
        return new SocksClient($server, new Connector($this->loop));
    }
}
