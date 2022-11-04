<?php

namespace Model;

use Entity\ThermostatMode;
use PDO;

/**
 * Class ThermostatModesManagerPDO
 * @package Model
 */
class ThermostatModesManagerPDO extends ManagerPDO
{
    /**
     * @param \PDO $dao
     */
    public function __construct(PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'thermostat_modes';
        $this->entity = new ThermostatMode();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getModeIds(): array
    {
        $sql = "SELECT id FROM $this->tableName";
        $q = $this->prepare($sql);
        $q->execute();
        $result = $q->fetchAll(PDO::FETCH_COLUMN, 0) ?: [];
        $q->closeCursor();

        return $result;
    }
}
