<?php

namespace App\Backend\Modules\Thermostat\Cron;

use App\Backend\Modules\Thermostat\Helper\Power;
use Entity\Thermostat;
use Exception;
use Model\Thermostat\SocketIoSender;
use Model\ThermostatManagerPDO;
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

    /** @var ThermostatManagerPDO */
    protected $thermostatManager;

    /** @var SocketIoSender */
    protected $socketIOSender;

    /** @var Power */
    protected $powerHelper;

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
        $this->thermostatManager = $managers->getManagerOf('Thermostat');
        $this->socketIOSender = new SocketIoSender($args['app']);
        $this->powerHelper = new Power();
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        if ($this->thermostatConfigHelper->getEnabled() !== 'yes') {
            return;
        }

        if (!$delay = $this->thermostatConfigHelper->getDelay() ?? 0) {
            throw new Exception('Delay is not set');
        }

        /** @var Thermostat $thermostat */
        if (!$thermostat = current($this->thermostatManager->getList())) {
            throw new Exception('No thermostat found');
        }

        echo $this->getDescription();
        if ($thermostat->pwr() || !$thermostat->getLastPwrOff()) {
            return;
        }

        if ($this->powerHelper->canTurnPwrOn($thermostat, $delay)) {
            $this->socketIOSender->sendPwrOn();
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if thermostat is powered off for a period, set on after that period' . PHP_EOL;
    }
}
