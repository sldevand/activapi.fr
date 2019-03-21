<?php

namespace Entity\Scenario;

use OCFram\Entity;

/**
 * Class ScenarioSequence
 * @package Entity\Scenario
 */
class ScenarioSequence extends Entity
{
    /**
     * @var int $scenarioId
     */
    protected $scenarioId;

    /**
     * @var int $sequenceId
     */
    protected $sequenceId;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'scenarioId' => $this->getScenarioId(),
            'sequenceId' => $this->getSequenceId()
        ];
    }

    /**
     * @return int
     */
    public function getScenarioId()
    {
        return $this->scenarioId;
    }

    /**
     * @return int
     */
    public function getSequenceId()
    {
        return $this->sequenceId;
    }

    /**
     * @param int $scenarioId
     * @return ScenarioSequence
     */
    public function setScenarioId($scenarioId)
    {
        $this->scenarioId = $scenarioId;
        return $this;
    }

    /**
     * @param int $sequenceId
     * @return ScenarioSequence
     */
    public function setSequenceId($sequenceId)
    {
        $this->sequenceId = $sequenceId;
        return $this;
    }
}
