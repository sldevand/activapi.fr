<?php

namespace Sldevand\Framework\Api;

/**
 * Trait Hydrator
 * @package Sldevand\Framework\Api
 */
trait Hydrator
{
    /**
     * @param array $data
     */
    public function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
}
