<?php

namespace OCFram;

/**
 * Class NotNullValidator
 * @package OCFram
 */
class NotNullValidator extends Validator
{
    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        return !empty($value);
    }
}