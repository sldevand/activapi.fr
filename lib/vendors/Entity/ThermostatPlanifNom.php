<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class ThermostatPlanifNom
 * @package Entity
 */
class ThermostatPlanifNom extends Entity
{
    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @param array $ignoreProperties
     * @return bool
     */
    public function isValid($ignoreProperties = [])
    {
        return !empty("nom");
    }

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return ThermostatPlanifNom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id(),
            'nom' => $this->nom
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nom();
    }
}
