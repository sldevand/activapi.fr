<?php

namespace SFram\Traits\Entity;

/**
 * Trait EntityTimestampableTrait
 * @package SFram\Traits\Entity
 */
trait EntityTimestampableTrait
{
    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $updatedAt;

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
