<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class Thermostat
 * @package Entity
 */
class Thermostat extends Entity
{
    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var int $modeid
     */
    protected $modeid;

    /**
     * @var ThermostatMode $mode
     */
    protected $mode;

    /**
     * @var int $sensorid
     */
    protected $sensorid;

    /**
     * @var Sensor $sensor
     */
    protected $sensor;

    /**
     * @var ThermostatPlanif $planning
     */
    protected $planning;

    /**
     * @var string $planningName
     */
    protected $planningName;

    /**
     * @var bool $manuel
     */
    protected $manuel;

    /**
     * @var float $consigne
     */
    protected $consigne;

    /**
     * @var float $delta
     */
    protected $delta;

    /**
     * @var bool $interne
     */
    protected $interne;

    /**
     * @var int $etat
     */
    protected $etat;

    /**
     * @var string $releve
     */
    protected $releve;

    /**
     * @var float $temperature
     */
    protected $temperature;

    /**
     * @var float $hygrometrie
     */
    protected $hygrometrie;

    /**
     * @var int
     */
    protected $pwr;

    /**
     * @var string
     */
    protected $lastTurnOn = '';

    /**
     * @var string $releve
     */
    protected $lastPwrOff = '';

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
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
    public function sensorid()
    {
        return $this->sensorid;
    }

    /**
     * @return Sensor
     */
    public function sensor()
    {
        return $this->sensor;
    }

    /**
     * @return string
     */
    public function planning()
    {
        return $this->planning;
    }

    /**
     * @return string
     */
    public function planningName()
    {
        return $this->planningName;
    }

    /**
     * @return bool
     */
    public function manuel()
    {
        return $this->manuel;
    }

    /**
     * @return bool
     */
    public function interne()
    {
        return $this->interne;
    }

    /**
     * @return string
     */
    public function releve()
    {
        return $this->releve;
    }

    /**
     * @return float
     */
    public function temperature()
    {
        return $this->temperature;
    }

    /**
     * @return float
     */
    public function hygrometrie()
    {
        return $this->hygrometrie;
    }

    /**
     * @return int
     */
    public function pwr()
    {
        return $this->pwr;
    }

    /**
     * @param string $nom
     * @return Thermostat
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @param int $modeid
     * @return Thermostat
     */
    public function setModeid($modeid)
    {
        $this->modeid = $modeid;
        return $this;
    }

    /**
     * @param ThermostatMode $mode
     * @return Thermostat
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @param int $sensorid
     * @return Thermostat
     */
    public function setSensorid($sensorid)
    {
        $this->sensorid = $sensorid;
        return $this;
    }

    /**
     * @param Sensor $sensor
     * @return Thermostat
     */
    public function setSensor($sensor)
    {
        $this->sensor = $sensor;
        return $this;
    }

    /**
     * @param string $planning
     * @return Thermostat
     */
    public function setPlanning(string $planning)
    {
        $this->planning = $planning;
        return $this;
    }

    /**
     * @param string $planningName
     * @return Thermostat
     */
    public function setPlanningName($planningName)
    {
        $this->planningName = $planningName;
        return $this;
    }

    /**
     * @param bool $manuel
     * @return Thermostat
     */
    public function setManuel($manuel)
    {
        $this->manuel = $manuel;
        return $this;
    }

    /**
     * @param float $consigne
     * @return Thermostat
     */
    public function setConsigne($consigne)
    {
        $this->consigne = $consigne;
        return $this;
    }

    /**
     * @param float $delta
     * @return Thermostat
     */
    public function setDelta($delta)
    {
        $this->delta = $delta;
        return $this;
    }

    /**
     * @param bool $interne
     * @return Thermostat
     */
    public function setInterne($interne)
    {
        $this->interne = $interne;
        return $this;
    }

    /**
     * @param int $etat
     * @return Thermostat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @param string $releve
     * @return Thermostat
     */
    public function setReleve($releve)
    {
        $this->releve = $releve;
        return $this;
    }

    /**
     * @param float $temperature
     * @return Thermostat
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
        return $this;
    }

    /**
     * @param float $hygrometrie
     * @return Thermostat
     */
    public function setHygrometrie($hygrometrie)
    {
        $this->hygrometrie = $hygrometrie;
        return $this;
    }

    /**
     * @param int $pwr
     * @return Thermostat
     */
    public function setPwr($pwr)
    {
        $this->pwr = $pwr;

        return $this;
    }

    /**
     * @param Thermostat $thermostat
     * @return bool
     */
    public function hasChanged(Thermostat $thermostat)
    {
        return ($thermostat->etat() != $this->etat() || $thermostat->consigne() != $this->consigne() || $thermostat->delta() != $this->delta());
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
     * @return mixed
     */
    public function getLastTurnOn()
    {
        return $this->lastTurnOn;
    }

    /**
     * @param $lastTurnOn
     * @return $this
     */
    public function setLastTurnOn($lastTurnOn): Thermostat
    {
        $this->lastTurnOn = $lastTurnOn;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastPwrOff(): string
    {
        return $this->lastPwrOff;
    }

    /**
     * @param string $lastPwrOff
     * @return Thermostat
     */
    public function setLastPwrOff(string $lastPwrOff): Thermostat
    {
        $this->lastPwrOff = $lastPwrOff;

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
            'mode' => $this->mode,
            'sensor' => $this->sensor,
            'modeid' => $this->modeid,
            'sensorid' => $this->sensorid,
            'planning' => $this->planning,
            'planningName' => $this->planningName,
            'manuel' => $this->manuel,
            'consigne' => $this->consigne,
            'delta' => $this->delta,
            'interne' => $this->interne,
            'etat' => $this->etat,
            'releve' => $this->releve,
            'temperature' => $this->temperature,
            'hygrometrie' => $this->hygrometrie,
            'pwr' => $this->pwr,
            'lastTurnOn' => $this->lastTurnOn,
            'lastPwrOff' => $this->lastPwrOff
        );
    }
}
