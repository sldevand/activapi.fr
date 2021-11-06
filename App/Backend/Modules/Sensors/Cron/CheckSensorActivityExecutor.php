<?php

namespace App\Backend\Modules\Sensors\Cron;

use App\Backend\Modules\Mailer\Helper\MailSender;
use Entity\Sensor;
use Exception;
use Model\SensorsManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sensors\Helper\Config as SensorsConfigHelper;
use Sensors\Helper\Data;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class CheckSensorActivityExecutor
 * @package App\Backend\Modules\Sensors\Cron
 */
class CheckSensorActivityExecutor implements ExecutorInterface
{
    /** @var SensorsManagerPDO */
    protected $manager;

    /** @var SensorsConfigHelper */
    protected $sensorConfigHelper;

    /** @var MailSender */
    protected $mailSender;

    /**
     * CheckSensorActivityExecutor constructor.
     * @param array|null $args
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __construct(?array $args = null)
    {
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->manager = $managers->getManagerOf('Sensors');
        $this->sensorConfigHelper = new SensorsConfigHelper($args['app'], $managers->getManagerOf('Configuration\Configuration'));
        $this->mailSender = new MailSender($args['app']);
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        echo $this->getDescription();
        $sensors = $this->manager->getList();

        $inactiveSensors = [];
        $underValueSensors = [];
        foreach ($sensors as $sensor) {
            $alerts = $this->sensorConfigHelper->getAlerts();
            $alertTime = $alerts['time-' . $sensor->id()] ?? Data::SENSOR_ACTIVITY_TIME;
            if ($this->manager->checkSensorActivity($sensor, $alertTime)) {
                $inactiveSensors[] = $this->manager->getUnique($sensor->id());
            }
            $alertValue = $alerts['value-' . $sensor->id()] ?? Data::SENSOR_ALERT_VALUE;
            if ($this->manager->isSensorValueUnder($sensor, $alertValue)) {
                $underValueSensors[] = $this->manager->getUnique($sensor->id());
            }
        }

        if ($inactiveSensors) {
            $sensorNames = $this->getSensorNames($inactiveSensors);
            $subject = "Activapi.fr : $sensorNames sensors are inactive";

            $this->sendMail($inactiveSensors, $subject);
        }

        if ($underValueSensors) {
            $sensorNames = $this->getSensorNames($underValueSensors);
            $subject = "Activapi.fr : $sensorNames sensors are too cold";
            $this->sendMail($underValueSensors, $subject);
        }
    }


    protected function sendMail (array $sensors, $subject) {
        ob_start();
        require BACKEND . '/Modules/Sensors/Templates/Mail/checkSensorActivity.phtml';
        $body = ob_get_clean();

        try {
            if (!$this->mailSender->sendMail($subject, $body)) {
                echo 'An error occured when sending mail';
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param Sensor[] $sensors
     * @return string
     */
    protected function getSensorNames(array $sensors): string
    {
        $sensorNames = [];
        foreach ($sensors as $sensor) {
            $sensorNames[] = $sensor->nom();
        }

        return implode(',', $sensorNames);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if sensors are inactive for a period, sends notification email' . PHP_EOL;
    }
}
