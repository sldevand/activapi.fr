<?php

namespace Model\Scenario;

use Model\ManagerPDO;
use OCFram\Entity;

/**
 * Class SequencesManagerPDO
 * @package Model\Scenario
 */
class SequencesManagerPDO extends ManagerPDO
{
    /**
     * SequencesManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'sequence';
    }

    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save(Entity $entity, $ignoreProperties = null)
    {
        return parent::save($entity, ['items']);
    }
}
