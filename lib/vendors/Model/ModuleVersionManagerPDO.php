<?php

namespace Model;

use Entity\ModuleVersion;

/**
 * Class ModuleVersionManagerPDO
 * @package Model
 */
class ModuleVersionManagerPDO extends ManagerPDO
{
    /**
     * ModuleVersionManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'module_version';
        $this->entity = new ModuleVersion();
    }
}
