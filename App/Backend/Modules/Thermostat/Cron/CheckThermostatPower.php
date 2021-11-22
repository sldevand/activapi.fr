<?php

namespace App\Backend\Modules\Thermostat\Cron;

use App\Backend\Modules\Mailer\Helper\MailSender;
use App\Backend\Modules\Thermostat\Helper\Power;
use Entity\Thermostat;
use Exception;
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

    /** @var Power */
    protected $powerHelper;

    /** @var MailSender */
    protected $mailSender;

    /**
     * CheckThermostatPower constructor.
     * @param array|null $args
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __construct(?array $args = null)
    {
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->thermostatConfigHelper = new ThermostatConfigHelper(
            $args['app'],
            $managers->getManagerOf('Configuration\Configuration')
        );
        $this->thermostatManager = $managers->getManagerOf('Thermostat');
        $this->powerHelper = new Power();
        $this->mailSender = new MailSender($args['app']);
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
        $emails = $this->thermostatConfigHelper->getPowerOffEmails();
        if (!$this->powerHelper->canSendPwrOnEmail($thermostat, $delay, $emails)) {
            return;
        }

        try {
            $subject = 'Thermostat Power is off';
            ob_start();
            require BACKEND . '/Modules/Thermostat/Templates/Mail/checkThermostatPower.phtml';
            $body = ob_get_clean();

            if (!$this->mailSender->sendMail($subject, $body)) {
                echo 'An error occured when sending mail';
                return;
            }
            $thermostat->setMailSent(1);
            $this->thermostatManager
                ->save(
                    $thermostat,
                    ['mode', 'sensor', 'planningName', 'temperature', 'hygrometrie', 'lastTurnOn']
                );
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if thermostat is powered off for a period, send mail after that period' . PHP_EOL;
    }
}
