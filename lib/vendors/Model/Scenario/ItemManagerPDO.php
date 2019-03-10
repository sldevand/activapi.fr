<?php

namespace Model\Scenario;

use Entity\Scenario\Item;
use Model\ManagerPDO;
use OCFram\Entity;

/**
 * Class ItemManagerPDO
 * @package Model\Scenario
 */
class ItemManagerPDO extends ManagerPDO
{
    /**
     * ItemManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'item';
        $this->entity = new Item();
    }

    /**
     * @param Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        return parent::save($entity, ['actionneur']);
    }
}
