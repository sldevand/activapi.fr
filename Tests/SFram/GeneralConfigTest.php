<?php

namespace Tests\SFram;

use PHPUnit\Framework\TestCase;
use SFram\GeneralConfig;

/**
 * Class GeneralConfigTest
 * @package Tests\SFram
 */
class GeneralConfigTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testConfigFile()
    {
        $config = new GeneralConfig(MODULE_VERSION_CONFIG_PATH);
        $version = $config->get('Scenario');
        self::assertTrue($version === '1.0.1');
    }
}
