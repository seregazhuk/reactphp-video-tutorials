<?php

namespace AsyncScraper;

final class Image
{
    public $id;

    public $title;

    public $resolution;

    public $source;

    public $tags;

    public function __construct(string $id, string $title, string $resolution, string $source, string ...$tags)
    {
        $this->id = $id;
        $this->title = $title;
        $this->resolution = $resolution;
        $this->source = $source;
        $this->tags = $tags;
    }
}
