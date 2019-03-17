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
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $config = new GeneralConfig(MODULE_VERSION_CONFIG_PATH);
        $moduleVersionManagerDao = new ModuleVersionManagerPDO(self::$db);
        self::$schemaUpdater = new SchemaUpdater($config, $moduleVersionManagerDao);
    }

    /**
     * @throws \Exception
     */
    public function testExecute()
    {
        $updated = self::$schemaUpdater->execute();

        self::assertTrue(count($updated), "Nothing has been updated");
    }
}
