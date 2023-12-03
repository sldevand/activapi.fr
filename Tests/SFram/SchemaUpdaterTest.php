<?php


namespace Tests\SFram;

use Model\ModuleVersionManagerPDO;
use SFram\GeneralConfig;
use SFram\SchemaUpdater;
use Tests\AbstractPDOTestCase;

class SchemaUpdaterTest extends AbstractPDOTestCase
{
    /** @var SchemaUpdater $schemaUpdater */
    public static $schemaUpdater;

    /**
     * @throws \Exception
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (!file_exists(MODULE_VERSION_SQL_PATH)) {
            return;
        }

        $script = file_get_contents(MODULE_VERSION_SQL_PATH);
        self::$db->exec($script);
    }

    /**
     * @throws \Exception
     */
    public function testExecute()
    {
        $schemaUpdater = $this->createSchemaUpdater(MODULE_VERSION_CONFIG_PATH);
        $updated = $schemaUpdater->execute();

        self::assertTrue(count($updated) > 0, "Nothing has been updated");
        self::assertTrue($updated[0]->getModuleName() === "Scenario", "Module name is not Scenario");
        self::assertTrue($updated[0]->getVersionNumber() === "1.0.1", "Module version is not 1.0.1");

        $schemaUpdater2 = $this->createSchemaUpdater(MODULE_VERSION_CONFIG2_PATH);
        $updated2 = $schemaUpdater2->execute();

        self::assertTrue(count($updated2) > 0, "Scenario with config 2 has not been updated to version 1.0.2");
        self::assertTrue($updated2[0]->getModuleName() === "Scenario", "Module name is not Scenario");
        self::assertTrue($updated2[0]->getVersionNumber() === "1.0.2", "Module version is not 1.0.2");

        $updated3 = $schemaUpdater2->execute();

        self::assertTrue(count($updated3) === 0, "Scenario with config 2 has been re-updated to version 1.0.2...");
    }

    /**
     * @param $configFilePaths
     * @return SchemaUpdater
     * @throws \Exception
     */
    public function createSchemaUpdater($configFilePaths)
    {
        $config = new GeneralConfig($configFilePaths);
        $moduleVersionManagerDao = new ModuleVersionManagerPDO(self::$db);

        return new SchemaUpdater($config, $moduleVersionManagerDao, MODULE_SQL_SCRIPT_DIR);
    }
}
