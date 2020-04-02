<?php

namespace Sldevand\User\Model\Entity;

use Framework\Model\Entity\AbstractEntity;
use Sldevand\Framework\Api\Hydrator;

/**
 * Class User
 * @package Sldevand\User\Model\Entity
 */
class UserEntity extends AbstractEntity
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

    /**
     * UserEntity constructor.
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        $this->hydrate($data);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserEntity
     */
    public function setEmail(string $email): UserEntity
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserEntity
     */
    public function setFirstName(string $firstName): UserEntity
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserEntity
     */
    public function setLastName(string $lastName): UserEntity
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserEntity
     */
    public function setPassword(string $password): UserEntity
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivationCode(): string
    {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     * @return UserEntity
     */
    public function setActivationCode(string $activationCode): UserEntity
    {
        $this->activationCode = $activationCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     * @return UserEntity
     */
    public function setActivated(bool $activated): UserEntity
    {
        $this->activated = $activated;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     * @return UserEntity
     */
    public function setCreatedAt(string $createdAt): UserEntity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     * @return UserEntity
     */
    public function setUpdatedAt(string $updatedAt): UserEntity
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
