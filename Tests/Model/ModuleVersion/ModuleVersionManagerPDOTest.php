<?php

namespace Tests\Model\ModuleVersion;

use Entity\ModuleVersion;
use Model\ModuleVersionManagerPDO;
use Tests\Model\AbstractManagerPDOTest;

/**
 * Class ModuleVersionManagerPDOTest
 * @package Tests\Model\ModuleVersion
 */
class ModuleVersionManagerPDOTest extends AbstractManagerPDOTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(MODULE_VERSION_SQL_PATH)) {
            $sql = file_get_contents(MODULE_VERSION_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @dataProvider saveProvider
     * @param ModuleVersion $moduleVersion
     * @param ModuleVersion $expected
     * @throws \Exception
     */
    public function testSave($moduleVersion, $expected)
    {
        $manager = $this->getManager();
        $manager->save($moduleVersion);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param ModuleVersion[] $moduleVersions
     * @param ModuleVersion[] $expected
     * @throws \Exception
     */
    public function testGetAll($moduleVersions, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($moduleVersions as $entity) {
            $manager->save($entity);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param ModuleVersion $moduleVersion
     * @param ModuleVersion $expected
     * @throws \Exception
     */
    public function testDelete($moduleVersion, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($moduleVersion);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }


    /**
     * @param string $moduleName
     * @param string $versionNumber
     * @param int | null $id
     * @return ModuleVersion
     */
    public function makeModuleVersion($moduleName, $versionNumber, $id = null)
    {
        return new ModuleVersion(
            [
                'id' => $id,
                'moduleName' => $moduleName,
                'versionNumber' => $versionNumber
            ]
        );
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        return [
            "createModuleVersion" => [
                $this->makeModuleVersion('Test', '1.0.1'),
                $this->makeModuleVersion('Test', '1.0.1', 1)
            ],
            "updateModuleVersion" => [
                $this->makeModuleVersion('Test', '1.0.2', 1),
                $this->makeModuleVersion('Test', '1.0.2', 1)
            ]
        ];
    }


    /**
     * @return array
     */
    public function getAllProvider()
    {
        return [
            "createSequences" => [
                [
                    $this->makeModuleVersion('Test1', '1.0.1'),
                    $this->makeModuleVersion('Test2', '1.0.2'),
                    $this->makeModuleVersion('Test3', '1.0.3'),
                    $this->makeModuleVersion('Test4', '1.0.4')
                ],
                [
                    $this->makeModuleVersion('Test1', '1.0.1', 1),
                    $this->makeModuleVersion('Test2', '1.0.2', 2),
                    $this->makeModuleVersion('Test3', '1.0.3', 3),
                    $this->makeModuleVersion('Test4', '1.0.4', 4)
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteProvider()
    {
        return [
            "deleteSequence" => [
                $this->makeModuleVersion('Test', '1.0.1'),
                $this->makeModuleVersion('Test', '1.0.1', 1)
            ]
        ];
    }

    /**
     * @return ModuleVersionManagerPDO
     */
    public function getManager()
    {
        return new ModuleVersionManagerPDO(self::$db);
    }
}
