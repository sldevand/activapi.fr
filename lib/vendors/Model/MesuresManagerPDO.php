<?php

namespace Model;

use Entity\Mesure;
use Entity\Sensor;

/**
 * Class MesuresManagerPDO
 * @package Model
 */
class MesuresManagerPDO extends ManagerPDO
{
    /**
     * MesuresManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'mesures';
        $this->entity = new Mesure();
    }

    /**
     * @param Mesure $mesure
     * @return bool
     * @throws \Exception
     */
    public function addWithSensorId(Mesure $mesure)
    {
        $q = $this->prepare('SELECT id FROM sensors WHERE radioid = :radioid');
        $q->bindValue(':radioid', $mesure->id_sensor());
        $q->execute();
        $id = $q->fetchColumn();
        $q->closeCursor();

        $q = $this->prepare(
            'INSERT INTO mesures (id_sensor, temperature, hygrometrie, horodatage) 
                       VALUES (:id_sensor,:temperature,:hygrometrie,DateTime("now","localtime"))'
        );
        $q->bindValue(':id_sensor', $id);
        $q->bindValue(':temperature', $mesure->temperature());
        $q->bindValue(':hygrometrie', $mesure->hygrometrie());
        $success = $q->execute();
        $q->closeCursor();

        return $success;
    }

    /**
     * @param int $debut
     * @param int $limite
     * @return array
     * @throws \Exception
     */
    public function getList($debut = 0, $limite = 50)
    {
        $sql = 'SELECT s.radioid id_sensor,s.nom, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id
			ORDER BY m.horodatage DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $q = $this->dao->query($sql);
        if (!$q) {
            throw new \Exception($this->dao->errorInfo());
        }

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');
        $listeMesure = $q->fetchAll();
        $q->closeCursor();

        return $listeMesure;
    }

    /**
     * @param Sensor $sensor
     * @param string $dateMin
     * @param string $dateMax
     * @return array
     * @throws \Exception
     */
    public function getSensorList($sensor, $dateMin, $dateMax)
    {
        $dateMin .= ' 00:00:00';
        $dateMax .= ' 00:00:00';

        $sql = 'SELECT s.radioid id_sensor,s.nom nom, s.id, m.temperature, m.hygrometrie, m.horodatage
			FROM sensors s
			INNER JOIN mesures m
			ON m.id_sensor = s.id
			AND s.radioid= :id_sensor
			AND horodatage >= :dateMin
			AND horodatage <= :dateMax
			ORDER BY horodatage ASC';

        $q = $this->prepare($sql);
        $q->bindParam(':id_sensor', $sensor);
        $q->bindParam(':dateMin', $dateMin);
        $q->bindParam(':dateMax', $dateMax);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Mesure');
        $listeMesure = $q->fetchAll();
        $q->closeCursor();

        return $listeMesure;
    }

    /**
     * @param mixed $value
     * @param string $field
     * @return array
     * @throws \Exception
     */
    public function getSensor($value, $field = 'radioid')
    {
        $q = $this->prepare("SELECT * FROM sensors WHERE $field = :$field");
        $q->bindParam(":$field", $value);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $sensor = $q->fetchAll();
        $q->closeCursor();

        return $sensor;
    }

    /**
     * @param $categorie
     * @return Sensor[]
     * @throws \Exception
     */
    public function getSensors($categorie)
    {
        $sql = "SELECT * FROM sensors";
        if ($categorie != "" && $categorie != null) {
            $sql .= " WHERE categorie=:categorie";
        }

        $q = $this->prepare($sql);
        if ($categorie != "" && $categorie != null) {
            $q->bindParam(":categorie", $categorie);
        }
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $sensors = $q->fetchAll();
        $q->closeCursor();

        return $sensors;
    }

    /**
     * @param $sensorEntity
     * @param $actif
     * @return bool
     * @throws \Exception
     */
    public function sensorActivityUpdate($sensorEntity, $actif)
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
}
