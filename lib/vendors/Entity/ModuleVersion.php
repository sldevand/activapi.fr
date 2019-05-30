<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class ModuleVersion
 * @package Entity
 */
class ModuleVersion extends Entity
{
    /**
     * @var string $moduleName
     */
    protected $moduleName;

    /**
     * @var string $versionNumber
     */
    protected $versionNumber;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'moduleName' => $this->getModuleName(),
            'versionNumber' => $this->getVersionNumber()
        ];
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     * @return ModuleVersion
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }

    /**
     * @param string $versionNumber
     * @return ModuleVersion
     */
    public function setVersionNumber($versionNumber)
    {
        $this->versionNumber = $versionNumber;

        return $this;
    }
}
