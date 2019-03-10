<?php


namespace Entity\Scenario;

use Entity\Actionneur;
use OCFram\Entity;

/**
 * Class Item
 * @package Entity\Scenario
 */
class Item extends Entity
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
     * @return int
     */
    public function getActionneurId()
    {
        return $this->actionneurId;
    }

    /**
     * @param int $actionneurId
     * @return Item
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
     * @return Item
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
     * @return Item
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }


}
