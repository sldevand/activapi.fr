<?php

namespace SFram;

use Entity\ModuleVersion;
use Model\ModuleVersionManagerPDO;
use SFram\Exception\SchemaUpdaterException;

/**
 * Class SchemaUpdater
 * @package SFram
 */
class SchemaUpdater
{
    use TransformVersionNumber;

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
        $updated = [];
        $moduleVersions = $this->moduleVersionManagerDao->getAll();
        $configModules = $this->config->getVars();

        foreach ($configModules as $configName => $configVersion) {
            /** @var ModuleVersion $moduleVersion */
            $moduleVersion = $this->searchInModuleVersionTable($moduleVersions, $configName);

            if (!$moduleVersion) {
                $updated[] = $this->saveAndExecuteScript($this->createModuleVersion($configName, $configVersion));
            } elseif ($moduleVersion && $this->toUpdate($moduleVersion, $configName, $configVersion)) {
                $this->saveAndExecuteScript($moduleVersion);
            }
        }

        return $updated;
    }

    /**
     * @param ModuleVersion $moduleVersion
     * @throws SchemaUpdaterException
     */
    public function executeScriptFromModuleVersion($moduleVersion)
    {
        $filename = $this->getSqlScriptFilename($moduleVersion);
        $script = $this->getSqlScript($filename);
        $this->executeSqlScript($script);
    }

    /**
     * @param ModuleVersion $moduleVersion
     * @return ModuleVersion
     * @throws SchemaUpdaterException
     * @throws \Exception
     */
    public function saveAndExecuteScript($moduleVersion)
    {
        $this->moduleVersionManagerDao->save($moduleVersion);
        $this->executeScriptFromModuleVersion($moduleVersion);
        return $moduleVersion;
    }

    /**
     * @param ModuleVersion $moduleVersion
     * @return string
     */
    public function getSqlScriptFilename($moduleVersion)
    {
        return ROOT . '/sql/' . $moduleVersion->getModuleName() . '-' . $moduleVersion->getVersionNumber() . '.sql';
    }

    /**
     * @param string $filename
     * @return string
     * @throws SchemaUpdaterException
     */
    public function getSqlScript($filename)
    {
        if (!file_exists($filename)) {
            throw new SchemaUpdaterException("SQL Script not found : $filename");
        }

        return file_get_contents($filename);
    }

    /**
     * @param string $script
     * @return int
     * @throws SchemaUpdaterException
     */
    public function executeSqlScript($script)
    {
        if (empty($script)) {
            throw new SchemaUpdaterException("No SQL Script to execute !");
        }

        return $this->moduleVersionManagerDao->getDao()->exec($script);
    }

    /**
     * @param ModuleVersion[] $moduleVersions
     * @param string $name
     * @return ModuleVersion | bool
     */
    public function searchInModuleVersionTable($moduleVersions, $name)
    {
        /** @var ModuleVersion $moduleVersion */
        foreach ($moduleVersions as $moduleVersion) {
            if ($moduleVersion->getModuleName() === $name) {
                return $moduleVersion;
            }
        }

        return false;
    }

    /**
     * @param ModuleVersion $moduleVersion
     * @param string $configName
     * @param string $configVersion
     * @return bool
     */
    public function toUpdate($moduleVersion, $configName, $configVersion)
    {
        $comparableModuleVersion = $this->getComparableVersionNumber($moduleVersion->getVersionNumber());
        $comparableConfigVersion = $this->getComparableVersionNumber($configVersion);

        if ($moduleVersion->getModuleName() === $configName
            && $comparableModuleVersion < $comparableConfigVersion
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $moduleName
     * @param string $versionNumber
     * @param null $id
     * @return ModuleVersion
     */
    public function createModuleVersion($moduleName, $versionNumber, $id = null)
    {
        return new ModuleVersion(
            [
                'id' => $id,
                'moduleName' => $moduleName,
                'versionNumber' => $versionNumber
            ]
        );
    }
}
