<?php

namespace Model;

use Entity\Sensor;
use OCFram\DateFactory;
use PDO;
use Sensors\Helper\Data;

/**
 * Class SensorsManagerPDO
 * @package Model
 */
class SensorsManagerPDO extends ManagerPDO
{
    /**
     * SensorsManagerPDO constructor.
     * @param PDO $dao
     */
    public function __construct($dao)
    {
        parent::__construct($dao);
        $this->tableName = 'sensors';
        $this->entity = new Sensor();
    }

    /**
     * @param string $categorie
     * @return \Entity\Sensor[]
     * @throws \Exception
     */
    public function getList($categorie = "")
    {
        $sql = "SELECT * FROM $this->tableName";
        if ($categorie) {
            $sql .= ' WHERE categorie = :categorie';
        }

        $q = $this->prepare($sql);
        if ($categorie) {
            $q->bindParam(':categorie', $categorie);
        }

        $q->execute();
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $sensors = $q->fetchAll();
        $q->closeCursor();

        return $sensors;
    }

    /**
     * @param \Entity\Sensor $sensorEntity
     * @param int $actif
     * @return bool
     * @throws \Exception
     */
    public function sensorActivityUpdate(Sensor $sensorEntity, int $actif): bool
    {
        if ($actif) {
            $sql = 'UPDATE sensors SET actif = :actif,  releve=DateTime("now","localtime"), valeur1=:valeur1, valeur2=:valeur2 WHERE radioid = :radioid';
        } else {
            $sql = 'UPDATE sensors SET actif = :actif WHERE radioid = :radioid';
        }

        $q = $this->prepare($sql);

        if ($actif) {
            $q->bindValue(':valeur1', $sensorEntity->valeur1());
            $q->bindValue(':valeur2', $sensorEntity->valeur2());
        }
        $q->bindValue(':actif', $actif);
        $q->bindValue(':radioid', $sensorEntity->radioid());
        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param \Entity\Sensor $sensor
     * @return bool
     * @throws \Exception
     */
    public function checkSensorActivity(Sensor $sensor): bool
    {
        if ($sensor->categorie() === Data::SENSOR_CATEGORY_DOOR) {
            return false;
        }

        $minutes = DateFactory::diffMinutesFromStr("now", $sensor->releve());
        if ($minutes >= Data::SENSOR_ACTIVITY_TIME && $sensor->actif()) {
            return $this->sensorActivityUpdate($sensor, 0);
        }

        return false;
    }
}
