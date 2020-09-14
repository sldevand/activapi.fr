<?php

namespace SFram\Traits\Repository;

use OCFram\Entity;
use SFram\Helpers\DateHelper;

/**
 * Trait RepositoryTimestampableTrait
 * @package SFram\Traits\Repository
 */
trait RepositoryTimestampableTrait
{
    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return Entity
     * @throws \Exception
     */
    public function add($entity, $ignoreProperties = null)
    {
        $entity->setCreatedAt(DateHelper::now());
        $entity->setUpdatedAt(DateHelper::now());
        return parent::add($entity);
    }

    /**
     * @param Entity $entity
     * @param null $ignoreProperties
     * @return Entity
     * @throws \Exception
     */
    public function update($entity, $ignoreProperties = null)
    {
        $entity->setUpdatedAt(DateHelper::now());
        return parent::update($entity);
    }
}
