<?php

namespace Tests\Model;

use Entity\Actionneur;
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
        self::executeSqlScript(SCENARIOS_SQL_PATH);
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

        self::expectException('Exception');
        self::expectExceptionMessage('No sequence was found!');

        $manager->getUnique($expected->id());
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        $actions = $this->mockActions();
        foreach ($actions as $key => $action) {
            $actions[$key]->setId($key + 1);
        }

        return [
            "createSequence" => [
                $this->makeSequence('Test1', $this->mockActions()),
                $this->makeSequence('Test1', $actions, '1')
            ],
            "updateSequence" => [
                $this->makeSequence('Test2', $actions, '1'),
                $this->makeSequence('Test2', $actions, '1'),
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
                    $this->makeSequence('Test1', $this->mockActions()),
                    $this->makeSequence('Test2', $this->mockActions()),
                    $this->makeSequence('Test3', $this->mockActions()),
                    $this->makeSequence('Test4', $this->mockActions())
                ],
                [
                    $this->makeSequence('Test1', $this->mockActions(1), 1),
                    $this->makeSequence('Test2', $this->mockActions(3), 2),
                    $this->makeSequence('Test3', $this->mockActions(5), 3),
                    $this->makeSequence('Test4', $this->mockActions(7), 4)
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
                $this->makeSequence('Test1', $this->mockActions()),
                $this->makeSequence('Test1', $this->mockActions(1), 1)
            ]
        ];
    }

    /**
     * @param null $id
     * @return array
     */
    public function mockActions($id = null)
    {
        /** @var Actionneur $actionneur */
        $actionneur = new Actionneur(
            [
                'id' => '1',
                'nom' => 'Salon',
                'module' => 'cc1101',
                'protocole' => 'chacon',
                'adresse' => '14549858',
                'type' => 'relay',
                'radioid' => 2,
                'etat' => 0,
                'categorie' => 'inter'
            ]
        );

        /** @var Actionneur $actionneur2 */
        $actionneur2 = new Actionneur(
            [
                'id' => '2',
                'nom' => 'Dalle_TV',
                'module' => 'bt',
                'protocole' => 'cnt',
                'adresse' => '00:00:00:00:00',
                'type' => 'blueLamp',
                'radioid' => 'val',
                'etat' => '210',
                'categorie' => 'dimmer'
            ]
        );

        if ($id) {
            return [
                $this->makeAction($actionneur, '0', $id),
                $this->makeAction($actionneur2, '100', $id + 1)
            ];
        }

        return [
            $this->makeAction($actionneur, '0'),
            $this->makeAction($actionneur2, '100')
        ];
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getManager()
    {
        return $this->getSequencesManager();
    }
}
