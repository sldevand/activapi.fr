<?php

namespace Model\Scenario;

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
    }

    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save(Entity $entity, $ignoreProperties = null)
    {
        return parent::save($entity, ['actionneur']);
    }
}
