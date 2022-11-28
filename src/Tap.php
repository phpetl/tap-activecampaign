<?php
declare(strict_types=1);

namespace PhpETL\Tap\ActiveCampaign;

use Dragonmantank\ActiveCampaign\Client;

class Tap
{
    public function __construct(
        protected Config $config,
        protected Client $client,
        protected ?string $catalog,
        protected ?string $stream)
    {
        
    }

    public function tap()
    {
        $catalogs = json_decode(file_get_contents($this->catalog), true);
        if ($this->stream) {
            $found = false;
            foreach ($catalogs['streams'] as $streamInfo) {
                if ($streamInfo['tap_stream_id'] === $this->stream) {
                    $catalogs['streams'] = [$streamInfo];
                    reset($catalogs);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('Invalid stream requested');
            }
        }
        $output = '';
        $state = ['type' => 'STATE', 'value' => []];

        foreach($catalogs['streams'] as $stream) {
            $schemaRecord = [
                'type' => 'SCHEMA',
                'stream' => $stream['stream'],
                'tap_stream_id' => $stream['tap_stream_id'],
                'schema' => $stream['schema'],
                'key_properties' => $stream['key_properties'],
                'bookmark_properties' => $stream['bookmark_properties'],
            ];
            $output .= json_encode($schemaRecord) . PHP_EOL;
            $state['value'][$stream['stream']] = 0;

            $response = $this->client->get($stream['stream']);
            $now = new \DateTimeImmutable();

            $baseRecord = [
                'type' => 'RECORD',
                'stream' => $stream['stream'],
                'time_extracted' => $now->format(DATE_ISO8601),
                'record' => []
            ];

            foreach ($response[$stream['stream']] as $item) {
                $newRecord = [];
                foreach ($stream['schema']['properties'] as $columnName => $columnType) {
                    $newRecord[$columnName] = $item[$columnName];
                }
                $record = $baseRecord;
                $record['record'] = $newRecord;
                $output .= json_encode($record) . PHP_EOL;
                $state['value'][$stream['stream']]++;
            }
            $output .= json_encode($state) . PHP_EOL;
        }

        return $output;
    }
}