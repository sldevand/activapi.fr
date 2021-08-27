<?php

namespace Sensors\Helper;

/**
 * Class Config
 * @package Sensors\Helper
 */
class Config extends \Helper\Configuration\Config
{
    const PATH_SENSORS_ALERT_ENABLE = 'sensors/alert/enable';
    const PATH_SENSORS_ALERT_TIMES = 'sensors/alert/times';

    /**
     * @return null|string
     */
    public function getEnabled(): ?string
    {
        return $this->getValue(self::PATH_SENSORS_ALERT_ENABLE);
    }

    /**
     * @return null|string
     */
    public function getAlertTimes(): ?string
    {
        return $this->getValue(self::PATH_SENSORS_ALERT_TIMES);
    }

    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        return parent::getConfigs(
            [
                self::PATH_SENSORS_ALERT_ENABLE,
                self::PATH_SENSORS_ALERT_TIMES
            ]
        );
    }
}
