<?php

namespace Thermostat\Helper;

/**
 * Class Config
 * @package Thermostat\Helper
 */
class Config extends \Helper\Configuration\Config
{
    const PATH_THERMOSTAT_ENABLE = 'thermostat/power/enable';
    const PATH_THERMOSTAT_DELAY = 'thermostat/power/delay';

    /**
     * @return null|string
     */
    public function getEnabled(): ?string
    {
        return $this->getValue(self::PATH_THERMOSTAT_ENABLE);
    }

    /**
     * @return null|string
     */
    public function getDelay(): ?string
    {
        return $this->getValue(self::PATH_THERMOSTAT_DELAY);
    }

    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        return parent::getConfigs(
            [
                self::PATH_THERMOSTAT_ENABLE,
                self::PATH_THERMOSTAT_DELAY
            ]
        );
    }
}
