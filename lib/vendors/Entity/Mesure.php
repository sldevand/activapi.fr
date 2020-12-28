<?php

namespace Entity;

use Exception;
use OCFram\Entity;

/**
 * Class Mesure
 * @package Entity
 */
class Mesure extends Entity
{
    /**
     * @var int $id_sensor
     */
    protected $id_sensor;

    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var float $temperature
     */
    protected $temperature;

    /**
     * @var float $hygrometrie
     */
    protected $hygrometrie = 0.0;

    /**
     * @var string $horodatage
     */
    protected $horodatage;


    /**
     * @return int
     */
    public function id_sensor()
    {
        return $this->id_sensor;
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
     * @return string
     */
    public function horodatage()
    {
        return $this->horodatage;
    }

    /**
     * @param int $id_sensor
     * @return Mesure
     * @throws Exception
     */
    public function setId_sensor($id_sensor)
    {
        if (empty($id_sensor) || !is_string($id_sensor)) {
            throw new Exception('idSensor invalide!');
        }

        $this->id_sensor = $id_sensor;

        return $this;
    }

    /**
     * @param string $nom
     * @return Mesure
     * @throws Exception
     */
    public function setNom($nom)
    {
        if (empty($nom) || !is_string($nom)) {
            throw new Exception('nom invalide!');
        }

        $this->nom = $nom;

        return $this;
    }

    /**
     * @param float $temperature
     * @return Mesure
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * @param float $hygrometrie
     * @return Mesure
     */
    public function setHygrometrie($hygrometrie)
    {
        $this->hygrometrie = $hygrometrie;

        return $this;
    }

    /**
     * @param string $horodatage
     * @return Mesure
     * @throws Exception
     */
    public function setHorodatage($horodatage)
    {
        if (empty($horodatage) || !is_string($horodatage)) {
            throw new Exception('horodatage invalide!');
        }
        $this->horodatage = $horodatage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'nom' => $this->nom,
            'radioid' => $this->id_sensor,
            'temperature' => $this->temperature,
            'hygrometrie' => $this->hygrometrie,
            'horodatage' => $this->horodatage
        ];
    }
}
