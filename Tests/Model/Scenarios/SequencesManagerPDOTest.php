<?php

namespace Tests\Model;

use Entity\Scenario\Sequence;
use Model\Scenario\SequencesManagerPDO;

/**
 * Class SequencesManagerPDOTest
 * @package Tests\Model
 */
class SequencesManagerPDOTest extends AbstractManagerPDOTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        if (file_exists(SCENARIOS_SQL_PATH)) {
            $sql = file_get_contents(SCENARIOS_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @dataProvider saveProvider
     * @param Sequence $expected
     * @param Sequence $sequence
     * @throws \Exception
     */
    public function testSave($sequence, $expected)
    {
        $manager = $this->getManager();
        $manager->save($sequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Sequence[] $expected
     * @param Sequence[] $sequences
     * @throws \Exception
     */
    public function testGetAll($sequences, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($sequences as $sequence) {
            $manager->save($sequence);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param Sequence $expected
     * @param Sequence $sequence
     * @throws \Exception
     */
    public function testDelete($sequence, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($sequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        return [
            "createSequence" => [
                $this->makeSequence('Test1', 150),
                $this->makeSequence('Test1', 150, 1)
            ],
            "updateSequence" => [
                $this->makeSequence('Test2', 150, 1),
                $this->makeSequence('Test2', 150, 1),
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
                    $this->makeSequence('Test1', 150),
                    $this->makeSequence('Test2', 225),
                    $this->makeSequence('Test3', 0),
                    $this->makeSequence('Test4', 120)
                ],
                [
                    $this->makeSequence('Test1', 150, 1),
                    $this->makeSequence('Test2', 225, 2),
                    $this->makeSequence('Test3', 0, 3),
                    $this->makeSequence('Test4', 120, 4)
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
                $this->makeSequence('Test1', 150),
                $this->makeSequence('Test1', 150, 1)
            ]
        ];
    }

    /**
     * @param $nom
     * @param $scenarioId
     * @param null | int $id
     * @return Sequence
     */
    public function makeSequence($nom, $scenarioId, $id = null)
    {
        return new Sequence(
            [
                'id' => $id,
                'nom' => $nom,
                'scenarioId' => $scenarioId
            ]
        );
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getManager()
    {
        return new SequencesManagerPDO(self::$db);
    }
}
