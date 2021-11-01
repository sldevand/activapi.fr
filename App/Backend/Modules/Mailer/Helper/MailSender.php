<?php

namespace App\Backend\Modules\Mailer\Helper;

use Mailer\Helper\Config;
use Mailer\MailerFactory;
use OCFram\Application;
use OCFram\ApplicationComponent;
use OCFram\Managers;
use OCFram\PDOFactory;

/**
 * Class MailSender
 * @package App\Backend\Modules\Mailer\Helper
 */
class MailSender extends ApplicationComponent
{
    /** @var \PHPMailer\PHPMailer\PHPMailer */
    protected $mailer;

    /** @var \Mailer\Helper\Config */
    protected $configHelper;

    /**
     * MailSender constructor.
     * @param Application $app
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->mailer = MailerFactory::create();
        $managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->configHelper = new Config($app, $managers->getManagerOf('Configuration\Configuration'));
    }

    /**
     * @param string $subject
     * @param string $body
     * @return string
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Exception
     */
    public function sendMail(string $subject, string $body)
    {
        if ($this->configHelper->getEnabled() !== 'yes') {
            throw new \Exception('Mailer module is not enabled');
        }

        if (!$mailAddress = $this->configHelper->getEmail()) {
            throw new \Exception('No mail was configured in Mailer module');
        }

        $this->mailer->Subject = $subject;
        $this->mailer->MsgHTML($body);
        $this->mailer->AddAddress($mailAddress);

        return $this->mailer->send();
    }
}
