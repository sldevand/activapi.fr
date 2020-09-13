<?php

namespace Helper\Configuration;

use Entity\Configuration\ConfigurationFactory;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\ApplicationComponent;

/**
 * Class Config
 * @package Helper\Configuration
 */
class Config extends ApplicationComponent
{
    /** @var \Model\Configuration\ConfigurationManagerPDO */
    protected $manager;

    /**
     * Config constructor.
     * @param \OCFram\Application $app
     * @param \Model\Configuration\ConfigurationManagerPDO $manager
     */
    public function __construct(\OCFram\Application $app, ConfigurationManagerPDO $manager)
    {
        parent::__construct($app);
        $this->manager = $manager;
    }

    /**
     * @param string $configKey
     * @return null|string
     */
    protected function getValue(string $configKey)
    {
        $configuration = $this->getConfiguration($configKey);

        return $configuration ? $configuration->getConfigValue() : null;
    }

    /**
     * @param string $configKey
     * @return \Entity\Configuration\Configuration|null
     */
    public function getConfiguration(string $configKey)
    {
        try {
            /** @var \Entity\Configuration\Configuration $configuration */
            $configuration = $this->manager->getUniqueBy('configKey', $configKey);
        } catch (\Exception $e) {
            return null;
        }

        return $configuration;
    }

    /**
     * @param array $configKeys
     * @return array|null
     */
    protected function getConfigs(array $configKeys)
    {
        $configurations = [];
        foreach ($configKeys as $configKey) {
            try {
                /** @var \Entity\Configuration\Configuration $configuration */
                $configuration = $this->manager->getUniqueBy('configKey', $configKey);
                $configurations[$configKey] = $configuration !== false
                    ? $configuration
                    : ConfigurationFactory::create();
            } catch (\Exception $e) {
                return [];
            }
        }

        return $configurations;
    }
}
