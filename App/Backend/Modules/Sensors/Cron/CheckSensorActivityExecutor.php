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

        $preparedSensors = [];
        foreach ($sensors as $sensor) {
            $alertTimes = $this->sensorConfigHelper->getAlertTimes();
            $alertTime = $alertTimes[$sensor->id()] ?? Data::SENSOR_ACTIVITY_TIME;
            if ($this->manager->checkSensorActivity($sensor, $alertTime)) {
                $preparedSensors[] = $this->prepareNotification($sensor->id());
            }
        }

        if ($preparedSensors) {
            $sensorNames = $this->getSensorNames($preparedSensors);
            $subject = "Activapi.fr : $sensorNames sensors are inactive";
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
    }

    /**
     * @param int $sensorId
     * @return null|\OCFram\Entity
     * @throws Exception
     */
    public function prepareNotification(int $sensorId)
    {
        return $this->manager->getUnique($sensorId);
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
