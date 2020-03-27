<?php

namespace Sldevand\Cron;

/**
 * Interface ExecutorInterface
 * @package Sldevand\Cron
 */
interface ExecutorInterface
{
    public function execute();

    public function getDescription();
}
