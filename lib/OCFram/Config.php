<?php

namespace OCFram;

/**
 * Class Config
 * @package OCFram
 */
class Config extends ApplicationComponent
{
    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @param $var
     * @return mixed
     * @throws \Exception
     */
    public function get($var)
    {
        return $this->getEnv(strtoupper($var));
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function getEnv(string $key)
    {
        if (!isset($_ENV[$key])) {
            throw new \Exception("$key environment variable does not exist");
        }

        return $_ENV[$key];
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }
}
