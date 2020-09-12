<?php

namespace Entity\Configuration;

use OCFram\Entity;

/**
 * Class Configuration
 * @package Entity\Configuration
 */
class Configuration extends Entity
{
    /** @var string */
    protected $configKey;

    /** @var string */
    protected $configValue;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'configKey'   => $this->getConfigKey(),
            'configValue' => $this->getConfigValue()
        ];
    }

    /**
     * @return string
     */
    public function getConfigKey(): ?string
    {
        return $this->configKey;
    }

    /**
     * @param string $configKey
     * @return Configuration
     */
    public function setConfigKey(string $configKey): Configuration
    {
        $this->configKey = $configKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigValue(): ?string
    {
        return $this->configValue;
    }

    /**
     * @param string $configValue
     * @return Configuration
     */
    public function setConfigValue(string $configValue): Configuration
    {
        $this->configValue = $configValue;

        return $this;
    }
}
