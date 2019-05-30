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
     * @var SequenceAction[] $sequenceActions
     */
    protected $sequenceActions;

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
            'actions' => $this->getActions()
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
     * @return SequenceAction[]
     */
    public function getSequenceActions()
    {
        return $this->sequenceActions;
    }

    /**
     * @param SequenceAction[] $sequenceActions
     * @return Sequence
     */
    public function setSequenceActions($sequenceActions)
    {
        $this->sequenceActions = $sequenceActions;
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
