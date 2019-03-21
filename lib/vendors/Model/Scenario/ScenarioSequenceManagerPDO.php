<?php

namespace Model\Scenario;

use Entity\Scenario\ScenarioSequence;
use Model\ManagerPDO;

/**
 * Class ScenarioSequenceManagerPDO
 * @package Model\Scenario
 */
class ScenarioSequenceManagerPDO extends ManagerPDO
{
    /**
     * ScenarioSequenceManagerPDO constructor.
     * @param \PDO $dao
     * @param $args
     */
    public function __construct(\PDO $dao, $args = [])
    {
        parent::__construct($dao, $args);
        $this->tableName = 'scenario_sequence';
        $this->entity = new ScenarioSequence();
    }
}
