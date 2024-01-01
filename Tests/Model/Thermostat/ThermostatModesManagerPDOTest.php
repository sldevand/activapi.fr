<?php

namespace Tests\Model\Thermostat;

use Entity\ThermostatMode;
use Model\ThermostatModesManagerPDO;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;
use Tests\Model\Thermostat\mock\ThermostatModesMock;

/**
 * Class ThermostatModesManagerPDOTest
 * @package Tests\Model\Thermostat
 */
class ThermostatModesManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(THERMOSTAT_MODES_SQL_PATH)) {
            $sql = file_get_contents(THERMOSTAT_MODES_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @return \Model\ThermostatModesManagerPDO
     */
    public function getManager()
    {
        return new ThermostatModesManagerPDO(self::$db);
    }

    /**
     * @dataProvider saveProvider
     * @param \Entity\ThermostatMode $thermostatMode
     * @param \Entity\ThermostatMode $expected
     * @throws \Exception
     */
    public function testSave($thermostatMode, $expected)
    {
        $manager = $this->getManager();
        $manager->save($thermostatMode);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @throws \Exception
     */
    public function testSaveWithInvalidEntity()
    {
        $manager = $this->getManager();
        $entity = unserialize(serialize(ThermostatModesMock::getThermostatModes()[0]));
        $entity->setConsigne(0);
        $this->expectExceptionMessage("in object " . ThermostatMode::class . " , consigne is empty");
        $manager->save($entity);
    }


    /**
     * @dataProvider getAllProvider
     * @param \Entity\ThermostatMode[] $thermostatModes
     * @param \Entity\ThermostatMode[] $expected
     * @throws \Exception
     */
    public function testGetAll($thermostatModes, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($thermostatModes as $thermostatMode) {
            $manager->save($thermostatMode);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param \Entity\ThermostatMode[] $thermostatModes
     * @param \Entity\ThermostatMode[] $expected
     * @throws \Exception
     */
    public function testGetList($thermostatModes, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($thermostatModes as $thermostatMode) {
            $manager->save($thermostatMode);
        }

        // Test filtered by categorie inter list
        $persisted = $manager->getList();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param \Entity\ThermostatMode $thermostatMode
     * @param \Entity\ThermostatMode $expected
     * @throws \Exception
     */
    public function testDelete($thermostatMode, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($thermostatMode);
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
        $entityCreated = ThermostatModesMock::getThermostatModes()[0];
        $expectedCreated = clone $entityCreated;
        $expectedCreated->setId(1);

        $entityUpdated = clone $expectedCreated;
        $entityUpdated->setConsigne(20.5);
        $entityUpdated->setDelta(0.5);

        return [
            "createThermostatMode" => [
                $entityCreated,
                $expectedCreated
            ],
            "updateThermostatMode" => [
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
        $entities = ThermostatModesMock::getThermostatModes();
        $expectedEntities = unserialize(serialize($entities));
        foreach ($expectedEntities as $key => $expectedEntity) {
            $expectedEntity->setId($key + 1);
        }

        return [
            "createThermostatModes" => [$entities, $expectedEntities]
        ];
    }

    /**
     * @return array[]
     */
    public function deleteProvider()
    {
        $entity = ThermostatModesMock::getThermostatModes()[0];
        $expected = clone $entity;
        $expected->setId(1);

        return [
            "deleteThermostatMode" => [$entity, $expected]
        ];
    }
}
