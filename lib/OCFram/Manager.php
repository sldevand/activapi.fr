<?php

namespace OCFram;

/**
 * Class Manager
 * @package OCFram
 */
abstract class Manager
{
    /**
     * @var \PDO
     */
    protected $dao;

    /**
     * Manager constructor.
     * @param \PDO $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \PDO
     */
    public function getDao()
    {
        return $this->dao;
    }
}
