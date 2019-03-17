<?php

namespace Model\Scenario;

use Entity\Scenario\Sequence;
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
        $this->entity = new Sequence();
    }

    /**
     * @param Entity $entity
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($entity, $ignoreProperties = [])
    {
        return parent::save($entity, ['actions']);
    }

    //TODO implement getAll here
}
