<?php
declare(strict_types=1);

namespace PhpETL\Tap\ActiveCampaign;

class Config
{
    public readonly string $key;
    public readonly string $url;

    static public function fromFile(string $path)
    {
        $json = json_decode(file_get_contents($path), true);

        $instance = new static();
        $instance->key = $json['key'];
        $instance->url = $json['url'];

        return $instance;
    }
}