<?php

namespace App\Backend\Modules\Sensors\Cron;

use Entity\Sensor;
use Exception;
use Mailer\Helper\Config;
use Sensors\Helper\Config as SensorsConfigHelper;
use Mailer\MailerFactory;
use Model\SensorsManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
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

    /** @var Config */
    protected $configHelper;

    /** @var SensorsConfigHelper */
    protected $sensorConfigHelper;

    /** @var \PHPMailer\PHPMailer\PHPMailer */
    protected $mailer;

    /**
     * CheckSensorActivityExecutor constructor.
     * @param array|null $args
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __construct(?array $args = null)
    {
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->manager = $managers->getManagerOf('Sensors');
        $this->configHelper = new Config($args['app'], $managers->getManagerOf('Configuration\Configuration'));
        $this->sensorConfigHelper = new SensorsConfigHelper($args['app'], $managers->getManagerOf('Configuration\Configuration'));
        $this->mailer = MailerFactory::create();
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
            echo $this->sendMail($preparedSensors);
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
     * @return mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendMail(array $sensors)
    {
        if ($this->configHelper->getEnabled() !== 'yes') {
            return 'Mailer module is not enabled';
        }

        if (!$mailAddress = $this->configHelper->getEmail()) {
            return 'No mail was configured in Mailer module';
        }

        if ($this->sensorConfigHelper->getEnabled() !== 'yes') {
            return 'Sensors mail alerts are disabled';
        }

        $sensorNames = $this->getSensorNames($sensors);
        $subject = "Activapi.fr : $sensorNames sensors are inactive";

        ob_start();
        require BACKEND . '/Modules/Sensors/Templates/Mail/checkSensorActivity.phtml';
        $body = ob_get_clean();

        $this->mailer->Subject = $subject;
        $this->mailer->MsgHTML($body);
        $this->mailer->AddAddress($mailAddress);

        $sent = $this->mailer->send();

        $message = $sent
            ? 'The message was successfully sent!'
            : 'An error occured when email was sent';

        return $message;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Check if sensors are inactive for a period, sends notification email' . PHP_EOL;
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
}
