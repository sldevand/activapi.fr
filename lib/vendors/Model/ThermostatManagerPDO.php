<?php

namespace Model;

use Entity\Sensor;
use Entity\Thermostat;
use Entity\ThermostatLog;
use Entity\ThermostatMode;
use OCFram\Managers;

/**
 * Class ThermostatManagerPDO
 * @package Model
 */
class ThermostatManagerPDO extends ManagerPDO
{
    /** @var \OCFram\Managers */
    protected $managers;

    /**
     * ThermostatManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat';
        $this->entity = new Thermostat();
        $this->managers = new Managers('PDO', $dao);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function count()
    {
        return $this->query("SELECT COUNT(*) FROM $this->tableName")->fetchColumn();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function countLogs()
    {
        return $this->query('SELECT COUNT(*) FROM thermostat_log')->fetchColumn();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getLastTurnOnLog(): string
    {
        return $this
            ->query('SELECT horodatage from thermostat_log where etat="1" order by id desc limit 1')
            ->fetchColumn() ?? '';
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


        /** @var \Model\ThermostatModesManagerPDO $modesManager */
        $modesManager = $this->managers->getManagerOf('ThermostatModes');
        /** @var \Model\SensorsManagerPDO $sensorsManager */
        $sensorsManager = $this->managers->getManagerOf('Sensors');

        /** @var Thermostat $thermostat */
        foreach ($listeThermostat as $thermostat) {
            $mode = $modesManager->getUnique($thermostat->modeid());
            $sensor = $sensorsManager->getUnique($thermostat->sensorid());
            if (!is_bool($mode)) {
                $thermostat->setMode($mode);
            }
            if (!is_bool($sensor)) {
                $thermostat->setSensor($sensor);
            }
        }

        return $listeThermostat;
    }

    /**
     * @param int $id
     * @return mixed|null|\OCFram\Entity
     * @throws \Exception
     */
    public function getUnique($id)
    {
        $q = $this->prepare('SELECT * FROM thermostat WHERE id = :id');
        $q->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Thermostat');
        $thermostat = $q->fetch();
        $q->closeCursor();

        return $thermostat;
    }

    /**
     * @param Thermostat $thermostat
     * @throws \Exception
     */
    public function modify(Thermostat $thermostat)
    {
        $lastPwrOffPart = $thermostat->getLastPwrOff() ? ', lastPwrOff = :lastPwrOff' : '';

        $q = $this->prepare('UPDATE thermostat SET nom = :nom, modeid = :modeid, sensorid = :sensorid, planning = :planning, manuel = :manuel, consigne = :consigne, delta = :delta, interne = :interne, etat = :etat, releve=DateTime("now","localtime"), pwr = :pwr ' . $lastPwrOffPart . ' WHERE id = :id');

        $q->bindValue(':id', $thermostat->id());
        $q->bindValue(':nom', $thermostat->nom());
        $q->bindValue(':modeid', $thermostat->modeid());
        $q->bindValue(':sensorid', $thermostat->sensorid());
        $q->bindValue(':planning', $thermostat->planning());
        $q->bindValue(':manuel', $thermostat->manuel());
        $q->bindValue(':consigne', $thermostat->consigne());
        $q->bindValue(':delta', $thermostat->delta());
        $q->bindValue(':interne', $thermostat->interne());
        $q->bindValue(':etat', $thermostat->etat());
        $q->bindValue(':pwr', $thermostat->pwr());
        if ($thermostat->getLastPwrOff()) {
            $q->bindValue(':lastPwrOff', $thermostat->getLastPwrOff());
        }
        $q->execute();
        $q->closeCursor();
    }

    /**
     * @param ThermostatLog $log
     * @return bool
     * @throws \Exception
     */
    public function addThermostatLog(ThermostatLog $log)
    {
        $q = $this->prepare('INSERT INTO thermostat_log (etat, horodatage,consigne,delta) VALUES (:etat,DateTime("now","localtime"),:consigne,:delta)');

        $q->bindValue(':etat', $log->etat());
        $q->bindValue(':consigne', $log->consigne());
        $q->bindValue(':delta', $log->delta());
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
    public function getLogList($debut = 0, $limite = 50)
    {
        $sql = 'SELECT * FROM thermostat_log ORDER BY horodatage DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $q = $this->query($sql);
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatLog');
        $listeLog = $q->fetchAll();
        $q->closeCursor();

        return $listeLog;
    }

    /**
     * @param string $dateMin
     * @param string $dateMax
     * @return array
     * @throws \Exception
     */
    public function getLogListWithDates($dateMin, $dateMax)
    {
        $sql = 'SELECT *
    FROM thermostat_log  
    WHERE horodatage >= :dateMin    
    AND horodatage <= :dateMax  
    ORDER BY horodatage ASC';

        $q = $this->prepare($sql);
        $q->bindValue(':dateMin', $dateMin);
        $q->bindValue(':dateMax', $dateMax);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\ThermostatLog');
        $listeLog = $q->fetchAll();
        $q->closeCursor();

        return $listeLog;
    }
}
