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
     * @var Sequence $sequence
     */
    protected $sequence;


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
     * @return Sequence
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param Sequence $sequence
     * @return Scenario
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

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
            'sequence' => $this->getSequence()
        ];
    }
}
