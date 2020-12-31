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
}
