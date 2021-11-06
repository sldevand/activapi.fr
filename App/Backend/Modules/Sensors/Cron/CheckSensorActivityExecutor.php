<?php

namespace App\Backend\Modules\Sensors\Cron;

use App\Backend\Modules\Mailer\Helper\MailSender;
use Entity\Notification\Notification;
use Entity\Sensor;
use Exception;
use Model\Notification\NotificationManagerPDO;
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
    protected $sensorsManager;

    /** @var NotificationManagerPDO */
    protected $notificationManager;

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
        $this->sensorsManager = $managers->getManagerOf('Sensors');
        $this->notificationManager = $managers->getManagerOf('Notification\Notification');
        $this->sensorConfigHelper = new SensorsConfigHelper($args['app'], $managers->getManagerOf('Configuration\Configuration'));
        $this->mailSender = new MailSender($args['app']);
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        echo $this->getDescription();
        $sensors = $this->sensorsManager->getList();

        $inactiveSensors = [];
        $underValueSensors = [];
        foreach ($sensors as $sensor) {
            $alerts = $this->sensorConfigHelper->getAlerts();
            $alertTime = $alerts['time-' . $sensor->id()] ?? Data::SENSOR_ACTIVITY_TIME;
            if ($this->sensorsManager->checkSensorActivity($sensor, $alertTime)) {
                $inactiveSensors[] = $this->sensorsManager->getUnique($sensor->id());
            }

            $this->manageUnderValueSensors($sensor, $underValueSensors);
        }

        if ($inactiveSensors) {
            $sensorNames = $this->getSensorNames($inactiveSensors);
            $subject = "Activapi.fr : $sensorNames sensors are inactive";
            $this->sendMail($inactiveSensors, $subject, 'inactive', true);
        }

        if ($underValueSensors) {
            $sensorNames = $this->getSensorNames($underValueSensors);
            $subject = "Activapi.fr : $sensorNames sensors are too cold";
            $this->sendMail($underValueSensors, $subject, 'underValue');
        }
    }

    /**
     * @param Sensor[] $sensors
     * @param string $subject
     * @param string $alertType
     * @param bool $ignoreNotifications
     */
    protected function sendMail(array $sensors, string $subject, string $alertType, bool $ignoreNotifications = false)
    {
        try {
            if (!$ignoreNotifications) {
                /** @var Notification[] $notifications */
                $notifications = $this->notificationManager->getListBy(Sensor::class, $alertType, true);
                $sensorIds = array_map(function ($sensor) {
                    return (int)$sensor->id();
                }, $sensors);
                if (!array_diff($sensorIds, array_keys($notifications))) {
                    return;
                }
            }

            ob_start();
            require BACKEND . '/Modules/Sensors/Templates/Mail/checkSensorActivity.phtml';
            $body = ob_get_clean();

            if (!$sent = $this->mailSender->sendMail($subject, $body)) {
                echo 'An error occured when sending mail';
            }
            if (!$ignoreNotifications) {
                $this->saveNotifications($sensors, $alertType, $sent);
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
     * @param Sensor[] $sensors
     * @param string $alertType
     * @param bool $sent
     * @throws Exception
     */
    protected function saveNotifications(array $sensors, string $alertType, bool $sent)
    {
        $notifications = $this->notificationManager->getListBy(Sensor::class, $alertType);
        foreach ($sensors as $sensor) {
            $notification = isset($notifications[$sensor->id()])
                ? $notifications[$sensor->id()]->setSent($sent)
                : new Notification(
                    [
                        'entityId' => $sensor->id(),
                        'entityType' => Sensor::class,
                        'alertType' => $alertType,
                        'sent' => $sent
                    ]
                );

            $this->notificationManager->save($notification);
        }
    }

    /**
     * @param Sensor $sensor
     * @param array $underValueSensors
     * @throws Exception
     */
    protected function manageUnderValueSensors(Sensor $sensor,array &$underValueSensors) {
        $alertValue = $alerts['value-' . $sensor->id()] ?? Data::SENSOR_ALERT_VALUE;
        if ($this->sensorsManager->isSensorValueUnder($sensor, $alertValue)) {
            $underValueSensors[] = $this->sensorsManager->getUnique($sensor->id());
        } else {
            $notification = current($this->notificationManager->getListBy(
                Sensor::class,
                'underValue',
                1,
                $sensor->id()
            ));

            if($notification && $notification->getSent()) {
                $notification->setSent(0);
                $this->notificationManager->save($notification);
            }
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if sensors are inactive for a period, sends notification email' . PHP_EOL;
    }
}
