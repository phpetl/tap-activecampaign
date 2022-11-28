<?php
declare(strict_types=1);

namespace PhpETLTest\Tap\ActvieCampaign;

use PhpETL\Tap\ActiveCampaign\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testLoadConfigFromFile()
    {
        $instance = Config::fromFile('test');
        $this->assertEquals('Test', $instance->orgName);
    }
}