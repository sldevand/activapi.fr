<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class ThermostatLog
 * @package Entity
 */
class ThermostatLog extends Entity
{
    /**
     * @var \DateTime $horodatage
     */
    protected $horodatage;

    /**
     * @var int $etat
     */
    protected $etat;

    /**
     * @var float $consigne
     */
    protected $consigne;

    /**
     * @var float $delta
     */
    protected $delta;


    /**
     * @return \DateTime
     */
    public function horodatage()
    {
        return $this->horodatage;
    }

    /**
     * @return int
     */
    public function etat()
    {
        return $this->etat;
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
     * @param \DateTime $horodatage
     * @return ThermostatLog
     */
    public function setHorodatage($horodatage)
    {
        $this->horodatage = $horodatage;

        return $this;
    }

    /**
     * @param int $etat
     * @return ThermostatLog
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @param float $consigne
     * @return ThermostatLog
     */
    public function setConsigne($consigne)
    {
        $this->consigne = $consigne;

        return $this;
    }

    /**
     * @param float $delta
     * @return ThermostatLog
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
            'horodatage' => $this->horodatage,
            'etat' => $this->etat,
            'consigne' => $this->consigne,
            'delta' => $this->delta
        );
    }
}
