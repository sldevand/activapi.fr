<?php

namespace Entity\User;

use OCFram\Entity;
use OCFram\Hydrator;

/**
 * Class User
 * @package Entity\User
 */
class User extends Entity
{
    use Hydrator;

    /** @var int */
    protected $id;

    /** @var string */
    protected $email;

    /** @var string */
    protected $firstName;

    /** @var string */
    protected $lastName;

    /** @var string */
    protected $password;

    /** @var string */
    protected $activationCode;

    /** @var bool */
    protected $activated;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $updatedAt;
}
