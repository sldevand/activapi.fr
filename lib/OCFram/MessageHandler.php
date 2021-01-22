<?php

namespace OCFram;

/**
 * Class MessageHandler
 * @package OCFram
 */
class MessageHandler
{
    /** @var array */
    protected $messages = [];

    /**
     * @param string $message
     * @return \OCFram\MessageHandler
     */
    public function addMessage(string $message): MessageHandler
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return \OCFram\MessageHandler
     */
    public function clearMessages(): MessageHandler
    {
        $this->messages = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
