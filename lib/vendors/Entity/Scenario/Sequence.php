<?php

namespace Entity\Scenario;

use OCFram\Entity;

/**
 * Class Sequence
 * @package Entity
 */
class Sequence extends Entity
{
    /**
     * @var string nom
     */
    protected $nom;

    /**
     * @var int $scenarioId
     */
    protected $scenarioId;

    /**
     * @var Action[] $actions
     */
    protected $actions;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'nom' => $this->getNom(),
            'sequence' => $this->getScenarioId()
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
     * @return Sequence
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return int
     */
    public function getScenarioId()
    {
        return $this->scenarioId;
    }

    /**
     * @param int $scenarioId
     * @return Sequence
     */
    public function setScenarioId($scenarioId)
    {
        $this->scenarioId = $scenarioId;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param Action[] $actions
     * @return Sequence
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }
}
