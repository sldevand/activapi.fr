<?php

namespace Mailer\Helper;

/**
 * Class Config
 * @package Mailer\Helper
 */
class Config extends \Helper\Configuration\Config
{
    const PATH_MAILER_ALERT_EMAIL = 'mailer/alert/email';
    const PATH_MAILER_ALERT_ENABLE = 'mailer/alert/enable';

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->getValue(self::PATH_MAILER_ALERT_EMAIL);
    }

    /**
     * @return null|string
     */
    public function getEnabled(): ?string
    {
        return $this->getValue(self::PATH_MAILER_ALERT_ENABLE);
    }

    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        return parent::getConfigs(
            [
                self::PATH_MAILER_ALERT_EMAIL,
                self::PATH_MAILER_ALERT_ENABLE
            ]
        );
    }
}
