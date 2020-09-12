<?php

namespace OCFram;

/**
 * Class Manager
 * @package OCFram
 */
abstract class Manager
{
    /**
     * @var \PDO $dao
     */
    protected $dao;

    /**
     * @var array $args
     */
    protected $args;

    /**
     * Manager constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct($dao, $args = [])
    {
        $this->dao = $dao;
        $this->args = $args;
    }

    /**
     * @return \PDO
     */
    public function getDao()
    {
        return $this->dao;
    }

    abstract public function save(Entity $entity);
}
