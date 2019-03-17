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
        if (file_exists(SCENARIOS_SQL_PATH)) {
            $sql = file_get_contents(SCENARIOS_SQL_PATH);
            self::$db->exec($sql);
        }
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
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        return [
            "createAction" => [
                $this->makeAction(1, 150),
                $this->makeAction(1, 150, 1)
            ],
            "updateAction" => [
                $this->makeAction(2, 180, 1),
                $this->makeAction(2, 180, 1)
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAllProvider()
    {
        return [
            "createActions" => [
                [
                    $this->makeAction(1, 150),
                    $this->makeAction(13, 225),
                    $this->makeAction(2, 0),
                    $this->makeAction(4, 120)
                ],
                [
                    $this->makeAction(1, 150, 1),
                    $this->makeAction(13, 225, 2),
                    $this->makeAction(2, 0, 3),
                    $this->makeAction(4, 120, 4)
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
            "deleteAction" => [
                $this->makeAction(1, 150),
                $this->makeAction(1, 150, 1)
            ]
        ];
    }

    /**
     * @param $actionneurId
     * @param $etat
     * @param null | int $id
     * @return Action
     */
    public function makeAction($actionneurId, $etat, $id = null)
    {
        return new Action(
            [
                'id' => $id,
                'actionneurId' => $actionneurId,
                new Actionneur(
                    ['id' => $actionneurId]
                ),
                'etat' => $etat
            ]
        );
    }

    /**
     * @return ActionManagerPDO
     */
    public function getManager()
    {
        /** @var ActionManagerPDO $manager */
        return new ActionManagerPDO(self::$db);
    }
}
