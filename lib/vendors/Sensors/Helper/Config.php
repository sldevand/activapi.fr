<?php

namespace Sensors\Helper;

/**
 * Class Config
 * @package Sensors\Helper
 */
class Config extends \Helper\Configuration\Config
{
    const PATH_SENSORS_ALERT_ENABLE = 'sensors/alert/enable';

    /**
     * @return null|string
     */
    public function getEnabled(): ?string
    {
        return $this->getValue(self::PATH_SENSORS_ALERT_ENABLE);
    }

    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        return parent::getConfigs(
            [
                self::PATH_SENSORS_ALERT_ENABLE
            ]
        );
    }
}
