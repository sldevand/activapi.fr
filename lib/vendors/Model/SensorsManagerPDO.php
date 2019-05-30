<?php

namespace Model;

use Entity\Sensor;
use PDO;

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
     * @return array
     * @throws \Exception
     */
    public function getList($categorie = "")
    {
        $sql = "SELECT * FROM $this->tableName";
        if ($categorie != "") {
            $sql .= ' WHERE categorie = :categorie';
        }

        $q = $this->prepare($sql);
        if ($categorie != "") {
            $q->bindParam(':categorie', $categorie);
        }

        $q->execute();
        $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $listeSensor = $q->fetchAll();
        $q->closeCursor();

        return $listeSensor;
    }
}
