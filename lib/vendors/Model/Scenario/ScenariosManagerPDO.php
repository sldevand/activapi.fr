<?php

namespace Model\Scenario;

use Model\ManagerPDO;
use OCFram\Entity;

/**
 * Class ScenariosManagerPDO
 * @package Model\Scenario
 */
class ScenariosManagerPDO extends ManagerPDO
{
    /**
     * ScenariosManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'scenario';
    }

    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save(Entity $entity, $ignoreProperties = null)
    {
        return parent::save($entity, ['sequence']);
    }
}
