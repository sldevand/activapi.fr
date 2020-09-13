<?php

namespace SFram\Console;

use OCFram\PDOFactory;
use SFram\OSDetectorFactory;
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
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        OSDetectorFactory::begin();

        $app = new \App\Backend\BackendApplication();

        $key = OSDetectorFactory::getPdoAddressKey();
        PDOFactory::setPdoAddress($app->config()->get($key));
    }
}
