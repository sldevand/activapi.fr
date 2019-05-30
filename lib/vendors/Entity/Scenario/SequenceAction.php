<?php

namespace Entity\Scenario;

use OCFram\Entity;

/**
 * Class SequenceAction
 * @package Entity\action
 */
class SequenceAction extends Entity
{
    /**
     * @var int $sequenceId
     */
    protected $sequenceId;

    /**
     * @var int $actionId
     */
    protected $actionId;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'actionId' => $this->getActionId(),
            'sequenceId' => $this->getSequenceId()
        ];
    }

    /**
     * @return int
     */
    public function getSequenceId()
    {
        return $this->sequenceId;
    }

    /**
     * @return int
     */
    public function getActionId()
    {
        return $this->actionId;
    }

    /**
     * @param int $sequenceId
     * @return SequenceAction
     */
    public function setSequenceId($sequenceId)
    {
        $this->sequenceId = $sequenceId;

        return $this;
    }

    /**
     * @param int $actionId
     * @return SequenceAction
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }
}
