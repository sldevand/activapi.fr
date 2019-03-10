<?php

namespace Model\Scenario;

use Entity\Scenario\Scenario;
use Model\ActionneursManagerPDO;
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
        $this->entity = new Scenario();
    }

    /**
     * @param Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        return parent::save($entity, ['sequence']);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getSequences($id)
    {
        //TODO implement get sequences here
        return [];
    }
}
