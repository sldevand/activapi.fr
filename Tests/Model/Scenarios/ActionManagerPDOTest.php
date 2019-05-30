<?php

namespace Tests\Model;

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
        $actionneurs = $this->mockActionneurs();

        return [
            "createAction" => [
                $this->makeAction('Test1', $actionneurs[0], 150),
                $this->makeAction('Test1', $actionneurs[0], 150, 1)
            ],
            "updateAction" => [
                $this->makeAction('Test2', $actionneurs[0], 180, 1),
                $this->makeAction('Test2', $actionneurs[0], 180, 1)
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllProvider()
    {
        $actionneurs = $this->mockActionneurs();

        return [
            "createActions" => [
                [
                    $this->makeAction('Test1', $actionneurs[0], 150),
                    $this->makeAction('Test2', $actionneurs[1], 225),
                    $this->makeAction('Test3', $actionneurs[2], 0),
                    $this->makeAction('Test4', $actionneurs[3], 120)
                ],
                [
                    $this->makeAction('Test1', $actionneurs[0], 150, 1),
                    $this->makeAction('Test2', $actionneurs[1], 225, 2),
                    $this->makeAction('Test3', $actionneurs[2], 0, 3),
                    $this->makeAction('Test4', $actionneurs[3], 120, 4)
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
        $actionneurs = $this->mockActionneurs();

        return [
            "deleteAction" => [
                $this->makeAction('Test1', $actionneurs[0], 150),
                $this->makeAction('Test1', $actionneurs[0], 150, 1)
            ]
        ];
    }

    /**
     * @return ActionManagerPDO
     */
    public function getManager()
    {
        return $this->getActionManager();
    }
}
