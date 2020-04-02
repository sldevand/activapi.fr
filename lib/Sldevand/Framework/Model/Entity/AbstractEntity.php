<?php

namespace Framework\Model\Entity;

use Framework\Api\EntityInterface;

/**
 * Class AbstractEntity
 * @package Framework\Model\Entity
 */
class AbstractEntity implements EntityInterface
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function setId(int $id): EntityInterface
    {
        $this->id = $id;

        return $this;
    }
}
