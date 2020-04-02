<?php

namespace Sldevand\User\Helper;

/**
 * Trait Hydrator
 * @package Sldevand\User\Helper
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
