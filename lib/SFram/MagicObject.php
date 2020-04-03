<?php

namespace SFram;

use Exception;

/**
 * Class MagicObject
 * @package Sfram
 */
class MagicObject
{
    /**
     * @param string $methodName
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function __call($methodName, $args)
    {
        if (!preg_match('~^(set|get)([A-Z].*)$~', $methodName, $matches)) {
            throw new Exception('Invalid method call :' . $methodName);
        }
        $property = lcfirst($matches[2]);
        $this->hasProperty($property);

        if ($matches[1] === 'set') {
            $this->$property = $args[0];
            return $this;
        }

        if ($matches[1] === 'get') {
            return $this->$property;
        }

        throw new Exception('Invalid method call :' . $methodName);
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function hasProperty(string $name): bool
    {
        $class = get_class($this);
        if (!property_exists($class, $name)) {
            throw new Exception(
                "$class::propertyExist --> The property $name does not exist!"
            );
        }

        return true;
    }

    /**
     * @param string $property
     * @return string
     */
    public function getPropertyMethod(string $property): string
    {
        return 'get' . ucfirst($property);
    }

    /**
     * @param string $property
     * @return string
     */
    public function setPropertyMethod(string $property): string
    {
        return 'set' . ucfirst($property);
    }
}
