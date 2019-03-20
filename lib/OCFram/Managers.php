<?php

namespace OCFram;

/**
 * Class Managers
 * @package OCFram
 */
class Managers
{
    /**
     * @var string $api
     */
    protected $api = null;

    /**
     * @var \PDO $dao
     */
    protected $dao = null;

    /**
     * @var array
     */
    protected $managers = [];

    /**
     * Managers constructor.
     * @param string $api
     * @param \PDO $dao
     */
    public function __construct($api, $dao)
    {
        $this->api = $api;
        $this->dao = $dao;
    }

    /**
     * @param string $module
     * @return mixed
     */
    public function getManagerOf($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module spécifié est invalide');
        }

        if (!isset($this->managers[$module])) {
            $manager = '\\Model\\' . $module . 'Manager' . $this->api;
            $this->managers[$module] = new $manager($this->dao);
        }

        return $this->managers[$module];
    }
}
