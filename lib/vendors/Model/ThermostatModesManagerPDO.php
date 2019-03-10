<?php

namespace Model;

use Entity\ThermostatMode;

/**
 * Class ThermostatModesManagerPDO
 * @package Model
 */
class ThermostatModesManagerPDO extends ManagerPDO
{
    /**
     * ThermostatModesManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat_modes';
        $this->entity = new ThermostatMode();
    }

    /**
     * @param \OCFram\Entity $thermostatMode
     * @param array $ignoreProperties
     * @throws \Exception
     */
    public function save($thermostatMode, $ignoreProperties = [])
    {
        if (!$thermostatMode->isValid($ignoreProperties)) {
            throw new \RuntimeException('Le thermostatMode doit être validé pour être enregistré');
        }

        $thermostatMode->isNew() ? $this->add($thermostatMode) : $this->modify($thermostatMode);
    }

    /**
     * @param \OCFram\Entity $thermostatMode
     * @param array $ignoreProperties
     * @return bool|void
     * @throws \Exception
     */
    public function add($thermostatMode, $ignoreProperties = [])
    {
        $sql = 'INSERT INTO thermostat_modes (nom,consigne,delta) 
              VALUES (:nom,:consigne,:delta)';
        $q = $this->prepare($sql);
        $q->bindValue(':consigne', $thermostatMode->consigne());
        $q->bindValue(':nom', $thermostatMode->nom());
        $q->bindValue(':delta', $thermostatMode->delta());
        $q->execute();
        $q->closeCursor();
    }

    /**
     * @param \OCFram\Entity $thermostatMode
     * @throws \Exception
     */
    public function modify($thermostatMode)
    {
        $sql = 'UPDATE thermostat_modes 
                SET nom=:nom, consigne=:consigne, delta=:delta                 
                WHERE id=:id';

        $q = $this->prepare($sql);
        $q->bindValue(':id', $thermostatMode->id());
        $q->bindValue(':consigne', $thermostatMode->consigne());
        $q->bindValue(':nom', $thermostatMode->nom());
        $q->bindValue(':delta', $thermostatMode->delta());

        $q->execute();
        $q->closeCursor();
    }
}
