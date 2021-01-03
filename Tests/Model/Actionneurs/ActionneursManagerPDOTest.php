<?php

namespace Tests\Model\Actionneurs;

use Model\ActionneursManagerPDO;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;
use Tests\Model\Actionneurs\mock\ActionneursMock;

/**
 * Class ActionneursManagerPDOTest
 * @package Tests\Model\Actionneurs
 */
class ActionneursManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(ACTIONNEURS_SQL_PATH)) {
            $sql = file_get_contents(ACTIONNEURS_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @return \Model\ActionneursManagerPDO
     */
    public function getManager()
    {
        return new ActionneursManagerPDO(self::$db);
    }

    /**
     * @dataProvider saveProvider
     * @param \Entity\Actionneur $actionneur
     * @param \Entity\Actionneur $expected
     * @throws \Exception
     */
    public function testSave($actionneur, $expected)
    {
        $manager = $this->getManager();
        $manager->save($actionneur);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param \Entity\Actionneur[] $actionneurs
     * @param \Entity\Actionneur[] $expected
     * @throws \Exception
     */
    public function testGetAll($actionneurs, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($actionneurs as $actionneur) {
            $manager->save($actionneur);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param \Entity\Actionneur[] $actionneurs
     * @param \Entity\Actionneur[] $expected
     * @throws \Exception
     */
    public function testGetList($actionneurs, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($actionneurs as $actionneur) {
            $manager->save($actionneur);
        }
        //Test getList
        $persisted = $manager->getList();
        self::assertEquals($expected, $persisted);

        // We remove the non inter categorie entities
        unset($expected[1]);
        $expected = array_values($expected);

        // Test filtered by categorie inter list
        $persisted = $manager->getList('inter');
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param \Entity\Actionneur $actionneur
     * @param \Entity\Actionneur $expected
     * @throws \Exception
     */
    public function testDelete($actionneur, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($actionneur);
        $manager->delete($expected->id());
        $sensor = $manager->getUnique($expected->id());
        self::assertFalse($sensor, '');
    }

    /**
     * @return array[]
     * @throws \Exception
     */
    public function saveProvider()
    {
        $entityCreated = ActionneursMock::getActionneurs()[0];
        $expectedCreated = clone $entityCreated;
        $expectedCreated->setId(1);

        $entityUpdated = clone $expectedCreated;
        $entityUpdated->setRadioid('4');

        return [
            "createActionneur" => [
                $entityCreated,
                $expectedCreated
            ],
            "updateActionneur" => [
                $entityUpdated,
                $entityUpdated
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function getAllProvider()
    {
        $entities = ActionneursMock::getActionneurs();
        $expectedEntities = unserialize(serialize($entities));
        foreach ($expectedEntities as $key => $expectedEntity) {
            $expectedEntity->setId($key + 1);
        }

        return [
            "createActionneurs" => [$entities, $expectedEntities]
        ];
    }

    /**
     * @return array[]
     */
    public function deleteProvider()
    {
        $entity = ActionneursMock::getActionneurs()[0];
        $expected = clone $entity;
        $expected->setId(1);

        return [
            "deleteActionneur" => [$entity, $expected]
        ];
    }
}