<?php

namespace Entity\Configuration;

use OCFram\Entity;

/**
 * Class Configuration
 * @package Entity\Configuration
 */
class Configuration extends Entity
{
    /** @var string */
    protected $key;

    /** @var string */
    protected $value;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'key' => $this->getKey(),
            'value' => $this->getValue()
        ];
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Configuration
     */
    public function setKey(string $key): Configuration
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Configuration
     */
    public function setValue(string $value): Configuration
    {
        $this->value = $value;

        return $this;
    }
}
