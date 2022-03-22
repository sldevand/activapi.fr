<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class InstalledSetup
 * @package Entity
 */
class InstalledSetup extends Entity
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'moduleName' => $this->getClassName(),
        ];
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return InstalledSetup
     */
    public function setClassName(string $className): InstalledSetup
    {
        $this->className = $className;

        return $this;
    }
}
