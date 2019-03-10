<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Item;
use Model\Scenario\ItemManagerPDO;

/**
 * Class ItemsManagerPDOTest
 * @package Tests\Models
 */
class ItemsManagerPDOTest extends AbstractManagerPDOTest
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
     * @dataProvider itemSaveProvider
     * @param Item $expected
     * @param Item $item
     * @throws \Exception
     */
    public function testSave($item, $expected)
    {
        $manager = $this->getManager();
        $manager->save($item);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider itemsGetAllProvider
     * @param Item[] $expected
     * @param Item[] $items
     * @throws \Exception
     */
    public function testGetAll($items, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($items as $item) {
            $manager->save($item);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider itemDeleteProvider
     * @param Item $expected
     * @param Item $item
     * @throws \Exception
     */
    public function testDelete($item, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($item);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return array
     */
    public function itemSaveProvider()
    {
        return [
            "createItem" => [
                $this->makeItem(1, 150),
                $this->makeItem(1, 150, 1)
            ],
            "updateItem" => [
                $this->makeItem(2, 180, 1),
                $this->makeItem(2, 180, 1)
            ]
        ];
    }

    /**
     * @return array
     */
    public function itemsGetAllProvider()
    {
        return [
            "createItems" => [
                [
                    $this->makeItem(1, 150),
                    $this->makeItem(13, 225),
                    $this->makeItem(2, 0),
                    $this->makeItem(4, 120)
                ],
                [
                    $this->makeItem(1, 150, 1),
                    $this->makeItem(13, 225, 2),
                    $this->makeItem(2, 0, 3),
                    $this->makeItem(4, 120, 4)
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function itemDeleteProvider()
    {
        return [
            "deleteItem" => [
                $this->makeItem(1, 150),
                $this->makeItem(1, 150, 1)
            ]
        ];
    }

    /**
     * @param $actionneurId
     * @param $etat
     * @param null | int $id
     * @return Item
     */
    public function makeItem($actionneurId, $etat, $id = null)
    {
        return new Item(
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
     * @return ItemManagerPDO
     */
    public function getManager()
    {
        /** @var ItemManagerPDO $manager */
        return new ItemManagerPDO(self::$db);
    }
}
