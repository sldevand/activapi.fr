<?php


namespace Entity\Scenario;

use Entity\Actionneur;
use Exception;
use OCFram\Entity;

/**
 * Class Action
 * @package Entity\Scenario
 */
class Action extends Entity
{
    /**
     * @var Actionneur $actionneur
     */
    protected $actionneur;

    /**
     * @var int $actionneurId
     */
    protected $actionneurId;

    /**
     * @var string $etat
     */
    protected $etat;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'actionneurId' => $this->getActionneur()->id(),
            'actionneur' => $this->getActionneur(),
            'etat' => $this->getEtat()
        ];
    }

    /**
     * @return Actionneur
     */
    public function getActionneur()
    {
        return $this->actionneur;
    }

    /**
     * @param Actionneur $actionneur
     * @return Action
     * @throws Exception
     */
    public function setActionneur($actionneur)
    {
        if (empty($actionneur)) {
            throw new Exception('No actionneur was set');
        }
        $this->actionneur = $actionneur;
        $this->actionneurId = $actionneur->id();

        return $this;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     * @return Action
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return int
     */
    public function getActionneurId()
    {
        return $this->actionneurId;
    }

    /**
     * @param int $actionneurId
     * @return Action
     */
    public function setActionneurId($actionneurId)
    {
        $this->actionneurId = $actionneurId;

        return $this;
    }
}
