<?php

namespace Model\Config;

/**
 * Class Confg
 * @package Model\Config
 */
class Confg
{
    /** @var string $key */
    protected $key;

    /** @var string $value */
    protected $value;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Confg
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Confg
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
