<?php

namespace SFram;

use Model\ModuleVersionManagerPDO;

/**
 * Class SchemaUpdater
 * @package SFram
 */
class SchemaUpdater
{
    /**
     * @var GeneralConfig $config
     */
    protected $config;

    /**
     * @var ModuleVersionManagerPDO $moduleVersionManagerDao
     */
    protected $moduleVersionManagerDao;

    /**
     * SchemaUpdater constructor.
     * @param GeneralConfig $config
     * @param ModuleVersionManagerPDO $moduleVersionManagerDao
     */
    public function __construct($config, $moduleVersionManagerDao)
    {
        $this->config = $config;
        $this->moduleVersionManagerDao = $moduleVersionManagerDao;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $moduleVersions = $this->moduleVersionManagerDao->getAll();
    }
}
