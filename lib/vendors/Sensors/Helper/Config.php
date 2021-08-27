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
     * @return array
     */
    public function getAlertTimes(): array
    {
        return json_decode($this->getValue(self::PATH_SENSORS_ALERT_TIMES), true) ?? [];
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
