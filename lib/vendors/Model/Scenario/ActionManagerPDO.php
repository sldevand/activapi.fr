<?php

namespace Model\Scenario;

use Entity\Scenario\Action;
use Model\ManagerPDO;
use OCFram\Entity;

/**
 * Class ActionManagerPDO
 * @package Model\Scenario
 */
class ActionManagerPDO extends ManagerPDO
{
    /**
     * ActionManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'action';
        $this->entity = new Action();
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
