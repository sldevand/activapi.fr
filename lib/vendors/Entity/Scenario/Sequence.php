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
     * @var Item[] $items
     */
    protected $items;

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
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     * @return Sequence
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }
}
