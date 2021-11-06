<?php

namespace Entity\Notification;

use OCFram\Entity;

/**
 * Class Notification
 * @package Entity\Notification
 */
class Notification extends Entity
{
    /** @var int */
    protected $entityId;

    /** @var string */
    protected $entityType;

    /** @var string */
    protected $alertType;

    /** @var int */
    protected $sent;
}
