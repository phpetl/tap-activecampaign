<?php
declare(strict_types=1);

use Dragonmantank\ActiveCampaign\Client;
use PhpETL\Tap\ActiveCampaign\Config;
use PhpETL\Tap\ActiveCampaign\Tap;
use Psr\Http\Message\RequestInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$opts = getopt('', [
    'config:',
    'state:',
    'catalog:',
    'stream:',
]);

if (!array_key_exists('config', $opts)) {
    echo "A config file is required" . PHP_EOL;
    exit(1);
}

if (array_key_exists('state', $opts)) {
    echo "State file is not currently implemented and is ignored" . PHP_EOL;
}

$catalog = '*';
if (array_key_exists('catalog', $opts)) {
    $catalog = $opts['catalog'];
}

$stream = null;
if (array_key_exists('stream', $opts)) {
    $stream = $opts['stream'];
}

$config = Config::fromFile($opts['config']);
$client = new Client($config->url, $config->key);
$tap = new Tap($config, $client, $catalog, $stream);
echo $tap->tap();