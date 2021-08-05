<?php

namespace Model\User;

use Entity\User\User;
use Model\ManagerPDO;
use SFram\Traits\Repository\RepositoryTimestampableTrait;

/**
 * Class UsersManagerPDO
 * @package Model\User
 */
class UsersManagerPDO extends ManagerPDO
{
    use RepositoryTimestampableTrait;

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

    /**
     * @return null|\OCFram\Entity
     * @throws \Exception
     */
    public function getAdminUser()
    {
        return $this->getUniqueBy('role', 'admin');
    }
}
