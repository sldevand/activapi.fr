<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class ThermostatPlanif
 * @package Entity
 */
class ThermostatPlanif extends Entity
{
    /**
     * @var int $nomid
     */
    protected $nomid;

    /**
     * @var \Entity\ThermostatPlanifNom  $nom
     */
    protected $nom;

    /**
     * @var string $jour
     */
    protected $jour;

    /**
     * @var int $modeid
     */
    protected $modeid;

    /**
     * @var ThermostatMode $mode
     */
    protected $mode;

    /**
     * @var int $defaultModeid
     */
    protected $defaultModeid;

    /**
     * @var ThermostatMode $defaultMode
     */
    protected $defaultMode;

    /**
     * @var string $heure1Start
     */
    protected $heure1Start;

    /**
     * @var string $heure1Stop
     */
    protected $heure1Stop;

    /**
     * @var string $heure2Start
     */
    protected $heure2Start;

    /**
     * @var string $heure2Stop
     */
    protected $heure2Stop;

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
    public function nomid()
    {
        return $this->nomid;
    }

    /**
     * @return \Entity\ThermostatPlanifNom
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function jour()
    {
        return $this->jour;
    }

    /**
     * @return int
     */
    public function modeid()
    {
        return $this->modeid;
    }

    /**
     * @return ThermostatMode
     */
    public function mode()
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function defaultModeid()
    {
        return $this->defaultModeid;
    }

    /**
     * @return ThermostatMode
     */
    public function defaultMode()
    {
        return $this->defaultMode;
    }

    /**
     * @return string
     */
    public function heure1Start()
    {
        return $this->heure1Start;
    }

    /**
     * @return string
     */
    public function heure1Stop()
    {
        return $this->heure1Stop;
    }

    /**
     * @return string
     */
    public function heure2Start()
    {
        return $this->heure2Start;
    }

    /**
     * @return string
     */
    public function heure2Stop()
    {
        return $this->heure2Stop;
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
     * @return int
     */
    public function getModeid()
    {
        return $this->modeid;
    }

    /**
     * @param int $modeid
     * @return ThermostatPlanif
     */
    public function setModeid($modeid)
    {
        $this->modeid = $modeid;

        return $this;
    }

    /**
     * @return ThermostatMode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param ThermostatMode $mode
     * @return ThermostatPlanif
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultModeid()
    {
        return $this->defaultModeid;
    }

    /**
     * @param int $defaultModeid
     * @return ThermostatPlanif
     */
    public function setDefaultModeid($defaultModeid)
    {
        $this->defaultModeid = $defaultModeid;

        return $this;
    }

    /**
     * @return ThermostatMode
     */
    public function getDefaultMode()
    {
        return $this->defaultMode;
    }

    /**
     * @param ThermostatMode $defaultMode
     * @return ThermostatPlanif
     */
    public function setDefaultMode($defaultMode)
    {
        $this->defaultMode = $defaultMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeure1Start()
    {
        return $this->heure1Start;
    }

    /**
     * @param string $heure1Start
     * @return ThermostatPlanif
     */
    public function setHeure1Start($heure1Start)
    {
        $this->heure1Start = $heure1Start;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeure1Stop()
    {
        return $this->heure1Stop;
    }

    /**
     * @param string $heure1Stop
     * @return ThermostatPlanif
     */
    public function setHeure1Stop($heure1Stop)
    {
        $this->heure1Stop = $heure1Stop;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeure2Start()
    {
        return $this->heure2Start;
    }

    /**
     * @param string $heure2Start
     * @return ThermostatPlanif
     */
    public function setHeure2Start($heure2Start)
    {
        $this->heure2Start = $heure2Start;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeure2Stop()
    {
        return $this->heure2Stop;
    }

    /**
     * @param string $heure2Stop
     * @return ThermostatPlanif
     */
    public function setHeure2Stop($heure2Stop)
    {
        $this->heure2Stop = $heure2Stop;

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
            'modeid' => $this->modeid,
            'mode' => $this->mode,
            'defaultModeid' => $this->defaultModeid,
            'defaultMode' => $this->defaultMode,
            'heure1Start' => $this->heure1Start,
            'heure1Stop' => $this->heure1Stop,
            'heure2Start' => $this->heure2Start,
            'heure2Stop' => $this->heure2Stop
        );
    }
}
