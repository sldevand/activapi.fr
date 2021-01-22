<?php

namespace SFram\Console;

use OCFram\PDOFactory;
use Symfony\Component\Console\Command\Command;

/**
 * Class BaseCommand
 * @package SFram\Console
 */
class BaseCommand extends Command
{
    /**
     * BaseCommand constructor.
     * @param null|string $name
     * @throws \Exception
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $app = new \App\Backend\BackendApplication();
        PDOFactory::setPdoAddress($app->config()->get('DB_PATH'));
    }
}
