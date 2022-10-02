<?php

namespace Entity\Scenario;

use OCFram\Entity;

/**
 * Class Scenario
 * @package Entity\Scenario
 */
class Scenario extends Entity
{
    /** @var string $nom */
    protected $nom;

    /** @var ScenarioSequence[] $scenarioSequences */
    protected $scenarioSequences;

    /** @var Sequence[] $sequences */
    protected $sequences;

    /** @var string $status */
    protected $status;

    /** @var int */
    protected $visibility = 0;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'nom' => $this->getNom(),
            'sequences' => $this->getSequences(),
            'status' => $this->getStatus(),
            'visibility' => $this->getVisibility()
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
     * @return Scenario
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Sequence[]
     */
    public function getSequences()
    {
        return $this->sequences;
    }

    /**
     * @param Sequence[] $sequences
     * @return Scenario
     */
    public function setSequences($sequences)
    {
        $this->sequences = $sequences;

        return $this;
    }

    /**
     * @param Sequence $sequence
     * @return Scenario
     */
    public function addSequence($sequence)
    {
        $this->sequences[$sequence->getNom()] = $sequence;

        return $this;
    }

    /**
     * @param string $nom
     * @return Scenario
     */
    public function removeSequence($nom)
    {
        unset($this->sequences[$nom]);

        return $this;
    }

    /**
     * @return ScenarioSequence[]
     */
    public function getScenarioSequences()
    {
        return $this->scenarioSequences;
    }

    /**
     * @param ScenarioSequence[] $scenarioSequences
     * @return Scenario
     */
    public function setScenarioSequences($scenarioSequences)
    {
        $this->scenarioSequences = $scenarioSequences;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getVisibility(): int
    {
        return $this->visibility;
    }

    /**
     * @param int $visibility
     * @return $this
     */
    public function setVisibility(int $visibility): Scenario
    {
        $this->visibility = $visibility;

        return $this;
    }
}
