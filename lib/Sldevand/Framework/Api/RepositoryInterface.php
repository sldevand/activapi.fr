<?php

namespace Framework\Api;

/**
 * Interface RepositoryInterface
 * @package Framework\Api
 */
interface RepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity): EntityInterface;

    /**
     * @param int | string $value
     * @param string $field
     * @return EntityInterface
     */
    public function get($value, string $field): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function update(EntityInterface $entity): EntityInterface;
}
