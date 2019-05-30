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
     * @var array $args
     */
    protected $args;

    /**
     * Managers constructor.
     * @param string $api
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct($api, $dao, $args = [])
    {
        $this->api = $api;
        $this->dao = $dao;
        $this->args = $args;
    }

    /**
     * @param string $module
     * @param array $args
     * @return mixed
     */
    public function getManagerOf($module, $args = [])
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module spécifié est invalide');
        }

        if (!isset($this->managers[$module])) {
            $manager = '\\Model\\' . $module . 'Manager' . $this->api;
            $this->managers[$module] = new $manager($this->dao, $args);
        }

        return $this->managers[$module];
    }
}
