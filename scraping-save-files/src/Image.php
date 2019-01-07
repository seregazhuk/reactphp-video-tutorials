<?php

namespace AsyncScraper;

final class Image
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $resolution;
    /**
     * @var string
     */
    public $source;
    /**
     * @var string[]
     */
    public $tags;

    public function __construct(string $id, string $title, string $resolution, string $source, string ...$tags)
    {
        $this->id = $id;
        $this->title = $title;
        $this->resolution = $resolution;
        $this->source = $source;
        $this->tags = $tags;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'tags' => json_encode($this->tags),
            'resolution' => $this->resolution,
            'source' => $this->source,
        ];
    }

    public function filename(): string
    {
        return basename($this->source);
    }
}
