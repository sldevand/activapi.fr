<?php


namespace Entity\Scenario;

use Entity\Actionneur;
use OCFram\Entity;

/**
 * Class Action
 * @package Entity\Scenario
 */
class Action extends Entity
{
    /**
     * @var int $actionneurId
     */
    protected $actionneurId;

    /**
     * @var Actionneur $actionneur
     */
    protected $actionneur;

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
            'actionneurId' => $this->getActionneurId(),
            'actionneur' => $this->getActionneur(),
            'etat' => $this->getEtat()
        ];
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
     */
    public function setActionneur($actionneur)
    {
        $this->actionneur = $actionneur;

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
}
