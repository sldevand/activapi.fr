<?php

namespace OCFram;

/**
 * Class Managers
 * @package OCFram
 */
class Managers
{
    /**
     * @var null
     */
    protected $api = null;

    /**
     * @var null
     */
    protected $dao = null;

    /**
     * @var array
     */
    protected $managers = [];

    /**
     * Managers constructor.
     * @param $api
     * @param $dao
     */
    public function __construct($api, $dao)
    {
        $this->api = $api;
        $this->dao = $dao;
    }

    /**
     * @param $module
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
