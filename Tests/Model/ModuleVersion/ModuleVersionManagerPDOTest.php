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
    public static function setUpBeforeClass()
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
     * @param ModuleVersion $entity
     * @param ModuleVersion $expected
     * @throws \Exception
     */
    public function testSave($entity, $expected)
    {
        $manager = $this->getManager();
        $manager->save($entity);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param ModuleVersion[] $entities
     * @param ModuleVersion[] $expected
     * @throws \Exception
     */
    public function testGetAll($entities, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($entities as $entity) {
            $manager->save($entity);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param ModuleVersion $entity
     * @param ModuleVersion $expected
     * @throws \Exception
     */
    public function testDelete($entity, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($entity);
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
