<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Action;
use Model\Scenario\ActionManagerPDO;

/**
 * Class ActionsManagerPDOTest
 * @package Tests\Model
 */
class ActionManagerPDOTest extends AbstractManagerPDOTest
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
     * @param Action $expected
     * @param Action $action
     * @throws \Exception
     */
    public function testSave($action, $expected)
    {
        $manager = $this->getManager();
        $manager->save($action);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Action[] $expected
     * @param Action[] $actions
     * @throws \Exception
     */
    public function testGetAll($actions, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($actions as $action) {
            $manager->save($action);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param Action $expected
     * @param Action $action
     * @throws \Exception
     */
    public function testDelete($action, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($action);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());

        self::expectException('Exception');
        self::expectExceptionMessage('No action found!');

        $manager->getUnique($expected->id());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function saveProvider()
    {
        return [
            "createAction" => [
                $this->makeAction($this->mockActionneur(), 150),
                $this->makeAction($this->mockActionneur(), 150, 1)
            ],
            "updateAction" => [
                $this->makeAction($this->mockActionneur(), 180, 1),
                $this->makeAction($this->mockActionneur(), 180, 1)
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllProvider()
    {
        return [
            "createActions" => [
                [
                    $this->makeAction($this->mockActionneur(), 150),
                    $this->makeAction($this->mockActionneur(), 225),
                    $this->makeAction($this->mockActionneur(), 0),
                    $this->makeAction($this->mockActionneur(), 120)
                ],
                [
                    $this->makeAction($this->mockActionneur(), 150, 1),
                    $this->makeAction($this->mockActionneur(), 225, 2),
                    $this->makeAction($this->mockActionneur(), 0, 3),
                    $this->makeAction($this->mockActionneur(), 120, 4)
                ]
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function deleteProvider()
    {
        return [
            "deleteAction" => [
                $this->makeAction($this->mockActionneur(), 150),
                $this->makeAction($this->mockActionneur(), 150, 1)
            ]
        ];
    }

    /**
     * @return \Entity\Actionneur
     * @throws \Exception
     */
    public function mockActionneur()
    {
        /** @var Actionneur $actionneur */
        return new Actionneur(
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
    }

    /**
     * @return ActionManagerPDO
     */
    public function getManager()
    {
        return $this->getActionManager();
    }
}
