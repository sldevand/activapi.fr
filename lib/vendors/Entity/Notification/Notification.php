<?php

namespace Entity\Notification;

use OCFram\Entity;
use SFram\Traits\Entity\EntityTimestampableTrait;

/**
 * Class Notification
 * @package Entity\Notification
 */
class Notification extends Entity
{
    use EntityTimestampableTrait;

    /** @var int */
    protected $entityId;

    /** @var string */
    protected $entityType;

    /** @var string */
    protected $alertType;

    /** @var int */
    protected $sent;
}
