<?php

namespace Model;

use Entity\Thermostat;
use Entity\ThermostatLog;

class ThermostatManagerPDO extends ManagerPDO
{
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat';
        $this->entity = new Thermostat();
    }

    public function count()
    {
        return $this->dao->query("SELECT COUNT(*) FROM $this->tableName")->fetchColumn();
    }

    public function countLogs()
    {
        return $this->dao->query('SELECT COUNT(*) FROM thermostat_log')->fetchColumn();
    }

    /**
     * @param int | null $id
     * @return array
     * @throws \Exception
     */
    public function getList($id = null)
    {
        $sql = "SELECT * FROM  $this->tableName";
        $q = $this->prepare($sql);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $listeThermostat = $q->fetchAll();
        $q->closeCursor();

        foreach ($listeThermostat as $key => $thermostat) {
            $mode = $this->getMode($thermostat->modeid());
            $sensor = $this->getSensor($thermostat->sensorid());
            if (!is_bool($mode)) {
                $thermostat->setMode($mode);
            }
            if (!is_bool($sensor)) {
                $thermostat->setSensor($sensor);
            }
        }

        return $listeThermostat;
    }

    public function getMode($id)
    {

        $q = $this->dao->prepare('SELECT * FROM thermostat_modes WHERE id = :id');
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
        $mode = $q->fetch();
        $q->closeCursor();
        return $mode;

    }

    public function getSensor($id)
    {

        $q = $this->dao->prepare('SELECT * FROM sensors WHERE id = :id');
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Sensor');
        $sensor = $q->fetch();
        $q->closeCursor();
        return $sensor;

    }

    public function getUnique($id)
    {
        $q = $this->dao->prepare('SELECT * FROM thermostat WHERE id = :id');
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Thermostat');
        $thermostat = $q->fetch();
        $q->closeCursor();
        return $thermostat;
    }

    public function modify(Thermostat $thermostat)
    {
        $q = $this->dao->prepare('UPDATE thermostat SET nom = :nom, modeid = :modeid, sensorid = :sensorid, planning = :planning, manuel = :manuel, consigne = :consigne, delta = :delta, interne = :interne, etat = :etat,releve=DateTime("now","localtime")  WHERE id = :id');

        $q->bindParam(':id', $thermostat->id());
        $q->bindParam(':nom', $thermostat->nom());
        $q->bindParam(':modeid', $thermostat->modeid());
        $q->bindParam(':sensorid', $thermostat->sensorid());
        $q->bindParam(':planning', $thermostat->planning());
        $q->bindParam(':manuel', $thermostat->manuel());
        $q->bindParam(':consigne', $thermostat->consigne());
        $q->bindParam(':delta', $thermostat->delta());
        $q->bindParam(':interne', $thermostat->interne());
        $q->bindParam(':etat', $thermostat->etat());
        $q->execute();
        $q->closeCursor();
    }

    public function addThermostatLog(ThermostatLog $log)
    {
        $q = $this->dao->prepare('INSERT INTO thermostat_log (etat, horodatage,consigne,delta) VALUES (:etat,DateTime("now","localtime"),:consigne,:delta)');

        $q->bindValue(':etat', $log->etat());
        $q->bindValue(':consigne', $log->consigne());
        $q->bindValue(':delta', $log->delta());
        $success = $q->execute();
        $q->closeCursor();
        return $success;

    }

    public function getLogList($debut = 0, $limite = 50)
    {

        $sql = 'SELECT * FROM thermostat_log ORDER BY horodatage DESC';


        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $q = $this->dao->query($sql);

        if ($q) {
            $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatLog');
            $listeLog = $q->fetchAll();
            $q->closeCursor();
        } else {

            echo "\nPDO::errorInfo():\n";
            print_r($this->dao->errorInfo());
        }
        return $listeLog;

    }

    public function getLogListWithDates($dateMin, $dateMax)
    {

        $sql = 'SELECT *
    FROM thermostat_log  
    WHERE horodatage >= :dateMin    
    AND horodatage <= :dateMax  
    ORDER BY horodatage ASC';


        $q = $this->dao->prepare($sql);

        if ($q) {
            $q->bindValue(':dateMin', $dateMin);
            $q->bindValue(':dateMax', $dateMax);
            $q->execute();
            $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatLog');
            $listeLog = $q->fetchAll();
            $q->closeCursor();

        } else {

            echo "\nPDO::errorInfo():\n";
            throw new \RuntimeException($this->dao->errorInfo());
        }
        return $listeLog;

    }

    public function getModes()
    {

        $q = $this->dao->prepare('SELECT * FROM thermostat_modes');
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatMode');
        $mode = $q->fetchAll();
        $q->closeCursor();
        return $mode;

    }


}


