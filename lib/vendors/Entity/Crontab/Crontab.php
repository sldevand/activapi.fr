<?php

namespace Entity\Crontab;

use OCFram\Entity;

class Crontab extends Entity
{
    /** @var string */
    protected $name;

    /** @var bool */
    protected $active;

    /** @var string */
    protected $expression;

    /** @var string */
    protected $executor;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->getName(),
            'expression' => $this->getExpression(),
            'active' => $this->isActive(),
            'executor' => $this->getExecutor()
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Crontab
     */
    public function setName(string $name): Crontab
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Crontab
     */
    public function setActive(bool $active): Crontab
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     * @return Crontab
     */
    public function setExpression(string $expression): Crontab
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @return string
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * @param string $executor
     * @return Crontab
     */
    public function setExecutor(string $executor): Crontab
    {
        $this->executor = $executor;
        return $this;
    }
}
