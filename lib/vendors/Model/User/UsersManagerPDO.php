<?php

namespace Model\User;

use Entity\User\User;
use Model\ManagerPDO;

/**
 * Class UsersManagerPDO
 * @package Model\User
 */
class UsersManagerPDO extends ManagerPDO
{
    /**
     * UsersManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'user';
        $this->entity = new User();
    }
}
