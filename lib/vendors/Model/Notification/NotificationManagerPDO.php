<?php

namespace Model\Notification;

use Entity\Notification\Notification;
use Model\ManagerPDO;

/**
 * Class NotificationManagerPDO
 * @package Model\Notification
 */
class NotificationManagerPDO extends ManagerPDO
{
    /**
     * NotificationManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'notification';
        $this->entity = new Notification();
    }
}
