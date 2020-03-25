<?php

namespace SFram\Cron;

use OCFram\PDOFactory;
use SFram\Cron\Node\Log\PurgeOldExecutor;
use SFram\Cron\Pool\Executors;
use SFram\OSDetectorFactory;

/**
 * Class Launcher
 * @package SFram\Cron
 */
class Launcher
{
    /** @var Executors */
    protected $executors;

    /** @var string */
    protected $periodicity;

    /**
     * Launcher constructor.
     * @param $argv
     * @throws \Exception
     */
    public function __construct($argv)
    {
        if (!$this->checkArguments($argv)) {
            die;
        }

        OSDetectorFactory::begin();

        $app = new \App\Backend\BackendApplication();

        $key = OSDetectorFactory::getPdoAddressKey();
        PDOFactory::setPdoAddress($app->config()->get($key));
        $this->executors = new Executors();

        $this->periodicity = $argv[1];
    }

    public function launch()
    {
        $this->prepareExecutors();
        $this->executors->execute($this->periodicity);
    }

    public function prepareExecutors()
    {
        $this->executors->addExecutor('day', 'remove_log_older_than_period', new PurgeOldExecutor());
    }

    /**
     * @param array $argv
     * @return bool
     */
    public function checkArguments($argv)
    {
        if (count($argv) === 1 || count($argv) > 2) {
            echo 'Too few arguments, 1 argument must be accepted!' . PHP_EOL;
            $this->help();

            return false;
        }

        if (!in_array($argv[1], ['minute', 'hour', 'day'])) {
            echo $argv[1] . ' is not an accepted argument' . PHP_EOL;
            $this->help();

            return false;
        }

        return true;
    }

    public function help()
    {
        echo 'Usage : cron.php <minute|hour|day>' . PHP_EOL;
    }

    /**
     * @param string $arg
     */
    public function unknownArg($arg)
    {
        echo "Periodicity $arg is unknown " . PHP_EOL;
        $this->help();
    }
}
