<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class Scenario
 * @package Entity
 */
class Scenario extends Entity
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
     * @var int $scenarioid
     */
    protected $scenarioid;

    /**
     * @var int $actionneurid
     */
    protected $actionneurid;

    /**
     * @var int $etat
     */
    protected $etat;

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @return Actionneur
     */
    public function actionneur()
    {
        return $this->actionneur;
    }

    /**
     * @return int
     */
    public function scenarioid()
    {
        return $this->scenarioid;
    }

    /**
     * @return int
     */
    public function actionneurid()
    {
        return $this->actionneurid;
    }

    /**
     * @return int
     */
    public function etat()
    {
        return $this->etat;
    }

    /**
     * @param string $nom
     * @return Scenario
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @param Actionneur $actionneur
     * @return Scenario
     */
    public function setActionneur(Actionneur $actionneur)
    {
        $this->actionneur = $actionneur;

        return $this;
    }

    /**
     * @param int $scenarioid
     * @return Scenario
     */
    public function setScenarioid($scenarioid)
    {
        $this->scenarioid = $scenarioid;

        return $this;
    }

    /**
     * @param int $actionneurid
     * @return Scenario
     */
    public function setActionneurid($actionneurid)
    {
        $this->actionneurid = $actionneurid;

        return $this;
    }

    /**
     * @param $etat
     * @return Scenario
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'nom' => $this->nom,
            'actionneur' => $this->actionneur,
            'scenarioid' => $this->scenarioid,
            'etat' => $this->etat
        );
    }
}
