<?php

namespace App\Backend\Modules\Thermostat\Cron;

use Exception;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\ExecutorInterface;
use Thermostat\Helper\Config as ThermostatConfigHelper;

/**
 * Class CheckThermostatPower
 * @package App\Backend\Modules\Thermostat\Cron
 */
class CheckThermostatPower implements ExecutorInterface
{

    /** @var ThermostatConfigHelper */
    protected $thermostatConfigHelper;

    /**
     * CheckThermostatPower constructor.
     * @param array|null $args
     */
    public function __construct(?array $args = null)
    {
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->thermostatConfigHelper = new ThermostatConfigHelper(
            $args['app'],
            $managers->getManagerOf('Configuration\Configuration')
        );
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->thermostatConfigHelper->getEnabled()) {
           return ;
        }
        echo $this->getDescription();

        //check if

    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if thermostat is powered off for a period, set on after that period' . PHP_EOL;
    }
}
