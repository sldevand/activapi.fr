<?php

namespace SFram\Commands;

use OCFram\Config;

/**
 * Class NodeActivator
 * @package SFram\Commands
 */
class NodeActivator
{
    /** @var Config $config */
    protected $config;

    /**
     * NodeActivator constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $status
     * @return string
     * @throws \Exception
     */
    public function toggle($status)
    {
        set_time_limit(0);
        $command = $this->selectStatusCommand($status);
        if (empty($command)) {
            return 'wrong status command';
        }
        exec($command, $output, $returnVar);

        return $returnVar;
    }

    /**
     * @param string $status
     * @return string
     * @throws \Exception
     */
    protected function selectStatusCommand($status)
    {
        if ($status === "on") {
            return $this->config->getEnv('NODE_START_COMMAND');
        }

        if ($status === "off") {
            return $this->config->getEnv('NODE_STOP_COMMAND');
        }

        return "";
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getStatus()
    {
        exec($this->config->getEnv('NODE_STATUS_COMMAND'), $output, $returnVar);
        if (isset($output[0]) && !$returnVar) {
            $output = current($output);

            return $output === 'active' ? 'on' : 'off';
        }

        return 'off';
    }
}
