<?php

namespace Model;

use Entity\InstalledSetup;

/**
 * Class InstalledSetupManagerPDO
 * @package Model
 */
class InstalledSetupManagerPDO extends ManagerPDO
{
    /**
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'installed_setup';
        $this->entity = new InstalledSetup();
    }
}
