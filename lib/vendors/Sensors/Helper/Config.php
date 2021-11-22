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
    const PATH_SENSORS_ALERT_UNDERVALUE_EMAILS = 'sensors/alert/undervalue_emails';
    const PATH_SENSORS_ALERT_ACTIVITY_EMAILS = 'sensors/alert/activity_emails';

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
    public function getAlerts(): array
    {
        return json_decode($this->getValue(self::PATH_SENSORS_ALERT_TIMES), true) ?? [];
    }

    /**
     * @return array
     */
    public function getUndervalueEmails(): array
    {
        return $this->cleanExplode(',' , $this->getValue(self::PATH_SENSORS_ALERT_UNDERVALUE_EMAILS));
    }

    /**
     * @return array
     */
    public function getActivityEmails(): array
    {
        return $this->cleanExplode(',' , $this->getValue(self::PATH_SENSORS_ALERT_ACTIVITY_EMAILS));
    }


    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        return parent::getConfigs(
            [
                self::PATH_SENSORS_ALERT_ENABLE,
                self::PATH_SENSORS_ALERT_TIMES,
                self::PATH_SENSORS_ALERT_UNDERVALUE_EMAILS,
                self::PATH_SENSORS_ALERT_ACTIVITY_EMAILS
            ]
        );
    }
}
