<?php

namespace SFram\Cron\Pool;

use SFram\Cron\ExecutorInterface;

/**
 * Class Executors
 * @package SFram\Cron\Pool
 */
class Executors
{
    /** @var ExecutorInterface[] */
    protected $executors;

    /**
     * @param string $pool
     * @throws \Exception
     */
    public function execute($pool)
    {
        if (empty($this->executors[$pool])) {
            throw new \Exception("The pool $pool does not exists !");
        }

        /** @var ExecutorInterface $executor */
        foreach ($this->executors[$pool] as $executor) {
            $executor->execute();
        }
    }

    /**
     * @param string $pool
     * @param string $key
     * @param ExecutorInterface $executor
     * @return Executors
     */
    public function addExecutor($pool, $key, $executor)
    {
        if (empty($this->executors[$pool][$key])) {
            $this->executors[$pool][$key] = $executor;
        }

        return $this;
    }

    /**
     * @param string $pool
     * @param string $key
     * @return ExecutorInterface | null
     * @throws \Exception
     */
    public function getExecutor($pool, $key)
    {
        if (empty($this->executors[$pool][$key])) {
            throw new \Exception("The executor $key does not exists !");
        }

        return $this->executors[$pool][$key];
    }
}
