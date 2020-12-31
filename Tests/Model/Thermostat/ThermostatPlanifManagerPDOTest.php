<?php

namespace Tests\Model\Thermostat;

use Entity\ThermostatPlanif;
use Model\ThermostatModesManagerPDO;
use Model\ThermostatPlanifManagerPDO;
use Tests\AbstractPDOTestCase;
use Tests\Api\ManagerPDOInterfaceTest;
use Tests\Model\Thermostat\mock\ThermostatModesMock;
use Tests\Model\Thermostat\mock\ThermostatPlanifMock;

/**
 * Class ThermostatPlanifManagerPDOTest
 * @package Tests\Model\Thermostat
 */
class ThermostatPlanifManagerPDOTest extends AbstractPDOTestCase implements ManagerPDOInterfaceTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
        if (file_exists(THERMOSTAT_MODES_SQL_PATH)) {
            $sql = file_get_contents(THERMOSTAT_MODES_SQL_PATH);
            self::$db->exec($sql);

            /** @var ThermostatModesManagerPDO $thermostatModesManager */
            $thermostatModesManager = self::$managers->getManagerOf('ThermostatModes');
            foreach (ThermostatModesMock::getThermostatModes() as $mode) {
                $thermostatModesManager->save($mode);
            }
        }
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(THERMOSTAT_PLANIF_SQL_PATH)) {
            $sql = file_get_contents(THERMOSTAT_PLANIF_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @return \Model\ThermostatPlanifManagerPDO
     */
    public function getManager()
    {
        return new ThermostatPlanifManagerPDO(self::$db);
    }

    /**
     * @return \Model\ThermostatPlanifManagerPDO
     */
    public function getThermostatModesManager()
    {
        return self::$managers->getManagerOf('ThermostatModes');
    }

    /**
     * @dataProvider saveProvider
     * @param \Entity\ThermostatPlanif $thermostatPlanif
     * @param \Entity\ThermostatPlanif $expected
     * @throws \Exception
     */
    public function testSave($thermostatPlanif, $expected)
    {
        $manager = $this->getManager();
        $manager->save($thermostatPlanif);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @throws \Exception
     */
    public function testModify()
    {
        $manager = $this->getManager();
        $thermostatPlanifNom = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        $thermostatPlanifNom->setId('1');
        $manager->addPlanifTable($thermostatPlanifNom);
        $thermostatPlanif = $manager->getUnique(1);
        $thermostatPlanif
            ->setHeure1Start('10:00:00')
            ->setHeure1Stop('18:00:00')
            ->setHeure2Start('18:00:00')
            ->setHeure2Stop('23:59:00');

        $result = $manager->modify($thermostatPlanif);
        self::assertTrue($result);
        $persisted = $manager->getUnique(1);
        self::assertEquals($thermostatPlanif, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param \Entity\ThermostatPlanif[] $thermostatPlanifs
     * @param \Entity\ThermostatPlanif[] $expected
     * @throws \Exception
     */
    public function testGetAll($thermostatPlanifs, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($thermostatPlanifs as $thermostatPlanif) {
            $manager->save($thermostatPlanif);
        }

        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getListProvider
     * @param \Entity\ThermostatPlanif[] $thermostatPlanifs
     * @param \Entity\ThermostatPlanif[] $expected
     * @throws \Exception
     */
    public function testGetList($thermostatPlanifs, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        foreach ($thermostatPlanifs as $thermostatPlanif) {
            $manager->save($thermostatPlanif);
        }

        // Test filtered by categorie inter list
        $persisted = $manager->getList();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param \Entity\ThermostatPlanif $thermostatPlanif
     * @param \Entity\ThermostatPlanif $expected
     * @throws \Exception
     */
    public function testDelete($thermostatPlanif, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($thermostatPlanif);
        $manager->delete($expected->id());
        $result = $manager->getUnique($expected->id());
        self::assertFalse($result);

        $result = $manager->getNom($expected->id());
        self::assertFalse($result);
    }

    /**
     * @throws \Exception
     */
    public function testAddNom()
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $expected = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        $expected->setId('1');

        $nameId = $manager->addNom($expected);
        self::assertEquals($expected->id(), $nameId);
    }

    /**
     * @throws \Exception
     */
    public function testAddPlanifTable()
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $expected = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        $expected->setId('1');
        $thermostatPlanifs = self::deepCopy(ThermostatPlanifMock::getDefaultThermostatPlanifs());
        $nameId = $manager->addPlanifTable($expected);
        self::assertEquals($expected->id(), $nameId);
        $list = $manager->getAll();
        self::assertEquals($thermostatPlanifs, $list);
    }

    /**
     * @throws \Exception
     */
    public function testGetNom()
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $thermostatPlanif = self::deepCopy(ThermostatPlanifMock::getThermostatPlanif()[0]);
        $savedId = $manager->save($thermostatPlanif);
        self::assertTrue($savedId === 1);

        $expected = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        $expected->setId('1');
        $name = $manager->getNom($savedId);
        self::assertEquals($expected, $name);
    }


    /**
     * @return array[]
     * @throws \Exception
     */
    public function saveProvider()
    {
        $modes = self::deepCopy(ThermostatModesMock::getThermostatModes());

        $entityCreated = ThermostatPlanifMock::getThermostatPlanif()[0];
        $nom = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        $expectedCreated = self::deepCopy($entityCreated);
        $expectedCreated->setId(1);
        $expectedCreated->setNom($nom->setId($expectedCreated->getNomid()));
        $expectedCreated->setMode($modes[0]->setId($expectedCreated->getModeId()));
        $expectedCreated->setDefaultMode($modes[2]->setId($expectedCreated->getDefaultModeId()));

        /** @var \Entity\ThermostatPlanif $entityUpdated */
        $entityUpdated = self::deepCopy($expectedCreated);
        $entityUpdated->setHeure1Start('06:00:00');
        $entityUpdated->setHeure1Stop('12:00:00');
        $entityUpdated->setHeure2Start('12:00:00');
        $entityUpdated->setHeure2Stop('18:00:00');

        return [
            "createThermostatPlanif" => [
                $entityCreated,
                $expectedCreated
            ],
            "updateThermostatPlanif" => [
                $entityUpdated,
                $entityUpdated
            ]
        ];
    }

    /**
     * @return array[]
     * @throws \Exception
     */
    public function getAllProvider()
    {
        $entities = ThermostatPlanifMock::getThermostatPlanif();
        $expectedEntities = self::deepCopy($entities);

        /**
         * @var int $key
         * @var \Entity\ThermostatPlanif $expectedEntity
         */
        foreach ($expectedEntities as $key => $expectedEntity) {
            $expectedEntity->setId($key + 1);
            $expectedEntity->setNomid(1);
            $expectedEntity->setNom(null);
            $expectedEntity->setMode(null);
            $expectedEntity->setDefaultMode(null);
        }

        return [
            "createThermostatPlanifs" => [$entities, $expectedEntities]
        ];
    }

    /**
     * @return array[]
     */
    public function getListProvider()
    {
        $entities = ThermostatPlanifMock::getThermostatPlanif();
        $expectedEntities = self::deepCopy($entities);
        $modes = self::deepCopy(ThermostatModesMock::getThermostatModes());
        $nom = self::deepCopy(ThermostatPlanifMock::getThermostatPlanifNom());
        /**
         * @var int $key
         * @var \Entity\ThermostatPlanif $expectedEntity
         */
        foreach ($expectedEntities as $key => $expectedEntity) {
            $expectedEntity->setId($key + 1);
            $expectedEntity->setNomid(1);
            $expectedEntity->setNom($nom->setId(1));
            $expectedEntity->setMode($modes[0]->setId(1));
            $expectedEntity->setDefaultMode($modes[2]->setId(3));
        }

        return [
            "createThermostatPlanifs" => [$entities, $expectedEntities]
        ];
    }

    /**
     * @return array[]
     */
    public function deleteProvider()
    {
        $entity = ThermostatPlanifMock::getThermostatPlanif()[0];
        $expected = self::deepCopy($entity);
        $expected->setId(1);

        return [
            "deleteThermostatPlanif" => [$entity, $expected]
        ];
    }
}
