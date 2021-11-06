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

    /**
     * @param string $entityType
     * @param string $alertType
     * @param bool $sent
     * @param int|null $entityId
     * @return array
     * @throws \Exception
     */
    public function getListBy(string $entityType, string $alertType, $sent = null, ?int $entityId = null)
    {
        $sql = 'SELECT * FROM notification';

        $values = [
            'entityType' => $entityType,
            'alertType' => $alertType
        ];

        if (!is_null($sent)) {
            $values['sent'] = $sent ? 1 : 0;
        }
        if ($entityId) {
            $values['entityId'] = $entityId;
        }

        foreach ($values as $field => $value) {
            $sql = $this->where($field, $value, $sql);
        }

        $q = $this->prepare($sql . ';');
        $this->bindProperties($q, $values);

        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $entities = $q->fetchAll();
        $q->closeCursor();

        $notifications = [];
        /** @var Notification $entity */
        foreach ($entities as $entity) {
            $notifications[$entity->getEntityId()] = $entity;
        }

        return $notifications;
    }
}
