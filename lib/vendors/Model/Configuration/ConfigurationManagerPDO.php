<?php

namespace Model\Configuration;

use Entity\Configuration\Configuration;
use Model\ManagerPDO;

/**
 * Class ConfigurationManagerPDO
 * @package Model\Configuration
 */
class ConfigurationManagerPDO extends ManagerPDO
{
    /**
     * ConfigurationManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'configuration';
        $this->entity = new Configuration();
    }
}
