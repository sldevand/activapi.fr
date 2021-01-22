<?php

namespace Tests\Model\Mesures;

use Entity\Mesure;
use Entity\Sensor;
use Model\MesuresManagerPDO;
use Model\SensorsManagerPDO;
use OCFram\DateFactory;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;
use Tests\Model\Mesures\mock\MesuresMock;
use Tests\Model\Sensor\mock\SensorsMock;

/**
 * Class MesuresManagerPDOTest
 * @package Tests\Model\Sensor
 */
class MesuresManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();

        if (file_exists(SENSOR_SQL_PATH)) {
            $sql = file_get_contents(SENSOR_SQL_PATH);
            self::$db->exec($sql);
            $sensors = SensorsMock::getSensors();
            foreach ($sensors as $sensor) {
                self::getSensorsManager()->save($sensor);
            }
        }
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(MESURES_SQL_PATH)) {
            $sql = file_get_contents(MESURES_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @return \Model\MesuresManagerPDO
     */
    public function getManager()
    {
        return new MesuresManagerPDO(self::$db);
    }

    /**
     * @return \Model\SensorsManagerPDO
     */
    public static function getSensorsManager()
    {
        return new SensorsManagerPDO(self::$db);
    }

    /**
     * @dataProvider saveProvider
     * @param \Entity\Mesure $mesure
     * @param Sensor $expected
     * @throws \Exception
     */
    public function testSave($mesure, $expected)
    {
        $manager = $this->getManager();
        $manager->save($mesure);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param \Entity\Mesure[] $mesures
     * @param \Entity\Mesure[] $expected
     * @throws \Exception
     */
    public function testGetAll($mesures, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($mesures as $mesure) {
            $manager->save($mesure);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @throws \Exception
     */
    public function testGetList()
    {
        self::dropAndCreateTables();
        $manager = self::getManager();
        $expected = $this->getExpectedForGetList($manager);
        $persisted = $manager->getList();
        self::assertEquals($expected, $persisted);
        $persistedLimit = $manager->getList(0, 3);
        self::assertCount(3, $persistedLimit);
        $persistedLimitAndStart = $manager->getList(2, 2);
        self::assertCount(2, $persistedLimitAndStart);
    }

    /**
     * @throws \Exception
     */
    public function testGetSensorList()
    {
        self::dropAndCreateTables();
        $manager = self::getManager();
        $now = DateFactory::todayToString();
        /** @var Sensor $sensor */
        $sensor = self::getSensorsManager()->getUnique('1');
        $expected = self::getExpectedForGetSensorList($manager, $sensor->id());
        $persisted = $manager->getSensorList($sensor->radioid(), $now, $now);

        self::assertEquals($expected, $persisted);
    }

    /**
     * @throws \Exception
     */
    public function testGetSensor()
    {
        self::dropAndCreateTables();
        $manager = self::getManager();
        $sensorsManager = self::getSensorsManager();
        /** @var Sensor $persistedSensor */
        $persistedSensor = $sensorsManager->getUnique(1);

        /** Get sensor by radioid */
        $sensor = $manager->getSensor($persistedSensor->radioid());
        self::assertEquals($persistedSensor, $sensor);

        /** Get sensor by id */
        $sensor = $manager->getSensor($persistedSensor->id(), 'id');
        self::assertEquals($persistedSensor, $sensor);
    }

    /**
     * @dataProvider deleteProvider
     * @param \Entity\Mesure $mesure
     * @param \Entity\Mesure $expected
     * @throws \Exception
     */
    public function testDelete($mesure, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($mesure);
        $manager->delete($expected->id());
        $mesure = $manager->getUnique($expected->id());
        self::assertFalse($mesure, '');
    }

    /**
     * @throws \Exception
     */
    public function testAddWithSensorId()
    {
        self::dropAndCreateTables();
        /** @var \Entity\Sensor $persistedSensor */
        $persistedSensor = self::getSensorsManager()->getUnique(1);
        $expected = new Mesure(
            [
                'id' => 1,
                'id_sensor' => $persistedSensor->id(),
                'temperature' => (float)$persistedSensor->valeur1(),
                'hygrometrie' => (float)$persistedSensor->valeur2(),
                'horodatage' => DateFactory::todayFullString()
            ]
        );

        $manager = $this->getManager();
        $result = $manager->addWithSensorId($persistedSensor);
        self::assertTrue($result);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @return array[]
     * @throws \Exception
     */
    public function saveProvider()
    {
        $entity = MesuresMock::getMesures()[0];
        $expectedCreate = clone $entity;
        $expectedCreate->setId('1');

        $expectedUpdated = clone $expectedCreate;
        $expectedUpdated->setHorodatage('2019-11-24 12:44:00');
        $expectedUpdated->setTemperature('11.6');

        return [
            "createMesure" => [
                $entity,
                $expectedCreate
            ],
            "updateMesure" => [
                $expectedUpdated,
                $expectedUpdated
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function getAllProvider()
    {
        $entities = MesuresMock::getMesures();
        $expectedMesures = unserialize(serialize($entities));
        foreach ($expectedMesures as $key => $expectedMesure) {
            $expectedMesure->setId($key + 1);
        }

        return [
            "getAllMesures" => [$entities, $expectedMesures]
        ];
    }

    /**
     * @return array[]
     */
    public function deleteProvider()
    {
        $entity = MesuresMock::getMesures()[0];
        $expected = clone $entity;
        $expected->setId(1);

        return [
            "deleteSensor" => [$entity, $expected]
        ];
    }

    /**
     * @return \Entity\Mesure[]
     * @throws \Exception
     */
    public function getExpectedForGetList($manager)
    {
        $sensorsManager = $this->getSensorsManager();

        $sensors = $sensorsManager->getList();
        $expected = [];
        foreach ($sensors as $key => $sensor) {
            $manager->addWithSensorId($sensor);
            $mesure = new Mesure(
                [
                    'id_sensor' => $sensor->radioid(),
                    'temperature' => (float)$sensor->valeur1(),
                    'hygrometrie' => (float)$sensor->valeur2(),
                    'horodatage' => DateFactory::todayFullString()
                ]
            );
            $mesure->setNom($sensor->nom());
            $expected[$key] = $mesure;
        }

        return $expected;
    }

    /**
     * @return \Entity\Mesure[]
     * @throws \Exception
     */
    public function getExpectedForGetSensorList($manager, $sensorId)
    {
        $sensorsManager = $this->getSensorsManager();

        $sensors = $sensorsManager->getList();
        $expected = [];
        foreach ($sensors as $key => $sensor) {
            $res = $manager->addWithSensorId($sensor);
            if ($sensor->id() != $sensorId || $res) {
                continue;
            }
            $mesure = new Mesure(
                [
                    'id_sensor' => $sensor->radioid(),
                    'temperature' => (float)$sensor->valeur1(),
                    'hygrometrie' => (float)$sensor->valeur2(),
                    'horodatage' => DateFactory::todayFullString()
                ]
            );
            $mesure->setNom($sensor->nom());
            $expected[] = $mesure;
        }

        return $expected;
    }
}