<?php

namespace OCFram;

use OCFram\Exception\PropertyNotExist;

/**
 * Trait Hydrator
 * @package OCFram
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
                try {
                    $this->$method($value);
                } catch (PropertyNotExist $exception) {
                    //Intentionnally empty
                }
            }
        }
    }
}
