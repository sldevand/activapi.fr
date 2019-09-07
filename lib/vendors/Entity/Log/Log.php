<?php

namespace Entity\Log;

use DateTime;
use OCFram\Entity;

/**
 * Class Log
 * @package Entity\Log
 */
class Log extends Entity
{
    /** @var string */
    protected $content;

    /** @var DateTime $createdAt */
    protected $createdAt;

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'content' => $this->getContent(),
            'createdAt' => $this->getCreatedAt()
        ];
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Log
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return Log
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
