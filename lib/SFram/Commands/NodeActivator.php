<?php

namespace SFram\Commands;

/**
 * Class NodeActivator
 * @package SFram\Commands
 */
class NodeActivator
{
    /** @var array $config */
    protected $config;

    /**
     * NodeActivator constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $status
     * @return string
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
     */
    protected function selectStatusCommand($status)
    {
        if ($status === "on") {
            return $this->config['toggleOnCommand'];
        }

        if ($status === "off") {
            return $this->config['toggleOffCommand'];
        }

        return "";
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        exec($this->config['getStatusCommand'], $output, $returnVar);
        if (isset($output) && !$returnVar && strpos($output[0], 'node') !== false) {
            return 'on';
        }

        return 'off';
    }
}
