<?php

namespace OCFram;

use RuntimeException;

/**
 * Class MaxLengthValidator
 * @package OCFram
 */
class MaxLengthValidator extends Validator
{
    /**
     * @var int $maxLength
     */
    protected $maxLength;

    /**
     * MaxLengthValidator constructor.
     * @param string $errorMessage
     * @param int $maxLength
     */
    public function __construct($errorMessage, $maxLength)
    {
        parent::__construct($errorMessage);

        $this->setMaxLength($maxLength);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        return strlen($value) <= $this->maxLength;
    }

    /**
     * @param int $maxLength
     * @throws RuntimeException
     */
    public function setMaxLength($maxLength)
    {
        $maxLength = (int)$maxLength;
        if ($maxLength <= 0) {
            throw new RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }

        $this->maxLength = $maxLength;
    }
}
