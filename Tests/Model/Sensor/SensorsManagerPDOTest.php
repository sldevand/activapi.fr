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
     * @param Sensor[] $entities
     * @param Sensor[] $expected
     * @throws \Exception
     */
    public function testGetAll($entities, $expected)
    {
        self::assertEquals(1, 1);
        // TODO: Implement testGetAll() method.
    }

    public function testDelete($entity, $expected)
    {
        self::assertEquals(1, 1);
        // TODO: Implement testDelete() method.
    }

    /**
     * @return array
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

    public function getAllProvider()
    {
        $sensorsEntities = SensorsMock::getSensors();
        $expectedSensors = [];
        $i = 1;
        foreach ($sensorsEntities as $key => $sensorsEntity) {
            $sensorsEntity->setId($i);
            $expectedSensors[$i - 1] = $sensorsEntity;
            $i++;
        }


        return [
            "createSensors" => [ $sensorsEntities , $expectedSensors]
        ];
    }

    public function deleteProvider()
    {
        // TODO: Implement deleteProvider() method.
    }
}