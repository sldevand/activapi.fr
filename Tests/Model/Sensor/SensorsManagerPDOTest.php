<?php

namespace Tests\Model\Sensor;

use Entity\Sensor;
use Model\SensorsManagerPDO;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;
use Tests\Model\Sensor\mock\SensorsMock;

/**
 * Class SensorsManagerPDOTest
 * @package Tests\Model\Sensor
 * @author Synolia <contact@synolia.com>
 */
class SensorsManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(SENSOR_SQL_PATH)) {
            $sql = file_get_contents(SENSOR_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @return \Model\SensorsManagerPDO
     */
    public function getManager()
    {
        return new SensorsManagerPDO(self::$db);
    }

    /**
     * @dataProvider saveProvider
     * @param Sensor $sensor
     * @param Sensor $expected
     * @throws \Exception
     */
    public function testSave($sensor, $expected)
    {
        $manager = $this->getManager();
        $manager->save($sensor);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Sensor[] $sensors
     * @param Sensor[] $expected
     * @throws \Exception
     */
    public function testGetAll($sensors, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($sensors as $sensor) {
            $manager->save($sensor);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Sensor[] $sensors
     * @param Sensor[] $expected
     * @throws \Exception
     */
    public function testGetList($sensors, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($sensors as $sensor) {
            $manager->save($sensor);
        }
        //Test getList
        $persisted = $manager->getList();
        self::assertEquals($expected, $persisted);

        // We remove the non thermo categorie entities
        unset($expected[3]);
        $expected = array_values($expected);

        // Test filtered by categorie thermo list
        $persisted = $manager->getList('thermo');
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param Sensor $sensor
     * @param Sensor $expected
     * @throws \Exception
     */
    public function testDelete($sensor, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($sensor);
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
        $entityCreateSensorData = SensorsMock::getSensors()[0];
        $expectedCreateSensorData = clone $entityCreateSensorData;
        $expectedCreateSensorData->setId(1);

        $entityUpdateSensorData = clone $expectedCreateSensorData;
        $entityUpdateSensorData->setRadioid('sensor24ctn10id2');

        return [
            "createSensor" => [
                $entityCreateSensorData,
                $expectedCreateSensorData
            ],
            "updateSensor" => [
                $entityUpdateSensorData,
                $entityUpdateSensorData
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function getAllProvider()
    {
        $sensorsEntities = SensorsMock::getSensors();
        $expectedSensors = unserialize(serialize($sensorsEntities));
        foreach ($expectedSensors as $key => $expectedSensor) {
            $expectedSensor->setId($key + 1);
        }

        return [
            "createSensors" => [$sensorsEntities, $expectedSensors]
        ];
    }

    /**
     * @return array[]
     */
    public function deleteProvider()
    {
        $sensor = SensorsMock::getSensors()[0];
        $expected = clone $sensor;
        $expected->setId(1);

        return [
            "deleteSensor" => [$sensor, $expected]
        ];
    }
}