<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class ThermostatMode
 * @package Entity
 */
class ThermostatMode extends Entity
{
    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var float $consigne
     */
    protected $consigne;

    /**
     * @var float $delta
     */
    protected $delta;

    /**
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function isValid($ignoreProperties = [])
    {
        parent::isValid($ignoreProperties);

        return !(empty($this->nom) || empty($this->consigne) || empty($this->delta));
    }

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @return float
     */
    public function consigne()
    {
        return $this->consigne;
    }

    /**
     * @return float
     */
    public function delta()
    {
        return $this->delta;
    }

    /**
     * @param string $nom
     * @return ThermostatMode
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @param float $consigne
     * @return ThermostatMode
     */
    public function setConsigne($consigne)
    {
        $this->consigne = $consigne;
        return $this;
    }

    /**
     * @param float $delta
     * @return ThermostatMode
     */
    public function setDelta($delta)
    {
        $this->delta = $delta;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {

        return array(
            'id' => $this->id(),
            'nom' => $this->nom,
            'consigne' => $this->consigne,
            'delta' => $this->delta
        );
    }
}
