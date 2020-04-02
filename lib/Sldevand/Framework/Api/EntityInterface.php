<?php

namespace Framework\Api;

/**
 * Interface EntityInterface
 * @package Framework\Api
 */
interface EntityInterface
{
    /**
     * @param int $id
     * @return EntityInterface
     */
    public function setId(int $id): EntityInterface;

    /**
     * @return int
     */
    public function getId(): int;
}
