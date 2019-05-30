<?php

namespace OCFram;

/**
 * Class Validator
 * @package OCFram
 */
abstract class Validator
{
    /**
     * @var string $errorMessage
     */
    protected $errorMessage;

    /**
     * Validator constructor.
     * @param string $errorMessage
     */
    public function __construct($errorMessage)
    {
        $this->setErrorMessage($errorMessage);
    }

    /**
     * @param string $value
     * @return bool
     */
    abstract public function isValid($value);

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        if (is_string($errorMessage)) {
            $this->errorMessage = $errorMessage;
        }
    }

    /**
     * @return string
     */
    public function errorMessage()
    {
        return $this->errorMessage;
    }
}
