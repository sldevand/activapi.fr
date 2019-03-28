<?php

namespace Entity\Scenario;

use OCFram\Entity;

/**
 * Class Scenario
 * @package Entity\Scenario
 */
class Scenario extends Entity
{
    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var Sequence[] $sequences
     */
    protected $sequences;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'nom' => $this->getNom(),
            'sequences' => $this->getSequences()
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
}
