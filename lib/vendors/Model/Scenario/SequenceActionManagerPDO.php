<?php

namespace Model\Scenario;

use Entity\Scenario\SequenceAction;
use Model\ManagerPDO;

/**
 * Class SequenceActionManagerPDO
 * @package Model\Scenario
 */
class SequenceActionManagerPDO extends ManagerPDO
{
    /**
     * SequenceActionManagerPDO constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct(\PDO $dao, $args = [])
    {
        parent::__construct($dao, $args);
        $this->tableName = 'sequence_action';
        $this->entity = new SequenceAction();
    }
}
