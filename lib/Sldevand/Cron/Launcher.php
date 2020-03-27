<?php

namespace Sldevand\Cron;

use Cron\CronExpression;

/**
 * Class Launcher
 * @package Sldevand\Cron
 */
class Launcher implements LauncherInterface
{
    /** @var array */
    protected $cronTab;

    /**
     * Launcher constructor.
     * @param array $cronTab
     */
    public function __construct(array $cronTab)
    {
        $this->cronTab = $cronTab;
    }

    public function launch()
    {
        foreach ($this->cronTab as $key => $cronJob) {
            $cron = CronExpression::factory($cronJob['expression']);
            if (!$cron->isDue()) {
                continue;
            }

            /** @var ExecutorInterface $executor */
            $executor = new $cronJob['executor']();
            $executor->execute();
        }
    }
}
