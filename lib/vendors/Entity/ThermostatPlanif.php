<?php

namespace Entity;

use OCFram\Entity;
use Entity\ThermostatPlanifNom;

/**
 * Class ThermostatPlanif
 * @package Entity
 */
class ThermostatPlanif extends Entity
{
    protected int $nomid;
    protected ThermostatPlanifNom $nom;
    protected string $jour;
    protected string $timetable;

    /**
     * @param array $ignoreProperties
     * @return bool
     */
    public function isValid($ignoreProperties = [])
    {
        return !empty("nom");
    }

    /**
     * @return int
     */
    public function getNomid()
    {
        return $this->nomid;
    }

    /**
     * @param int $nomid
     * @return ThermostatPlanif
     */
    public function setNomid($nomid)
    {
        $this->nomid = $nomid;
        return $this;
    }

    /**
     * @return \Entity\ThermostatPlanifNom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param \Entity\ThermostatPlanifNom $nom
     * @return ThermostatPlanif
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * @param string $jour
     * @return ThermostatPlanif
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id(),
            'nomid' => $this->nomid,
            'nom' => $this->nom,
            'jour' => $this->jour,
            'timetable' => $this->timetable
        );
    }

    public function getTimetable(): string
    {
        return $this->timetable;
    }
 
    public function setTimetable(string $timetable): ThermostatPlanif
    {
        $this->timetable = $timetable;

        return $this;
    }
}
