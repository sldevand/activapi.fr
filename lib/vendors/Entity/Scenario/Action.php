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
     * @var string $nom
     */
    protected $nom;


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
     * @var float $timeout
     */
    protected $timeout;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'nom' => $this->getNom(),
            'actionneurId' => $this->getActionneur()->id(),
            'actionneur' => $this->getActionneur(),
            'etat' => $this->getEtat()
        ];
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Action
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

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

    /**
     * @return float
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param float $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
