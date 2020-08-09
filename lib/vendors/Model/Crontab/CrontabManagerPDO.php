<?php

namespace Model\Crontab;

use Entity\Crontab\Crontab;
use Model\ManagerPDO;

class CrontabManagerPDO extends ManagerPDO
{
    /**
     * ActionneursManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'crontab';
        $this->entity = new Crontab();
    }
}
