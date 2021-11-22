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
     * @param array $mails
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendMail(string $subject, string $body, array $mails = [])
    {
        if ($this->configHelper->getEnabled() !== 'yes') {
            throw new \Exception('Mailer module is not enabled');
        }

        if (!$mailerEmail = $this->configHelper->getEmail()) {
            throw new \Exception('No mail was configured in Mailer module');
        }

        $mails = array_merge([$mailerEmail], $mails);
        $this->mailer->Subject = $subject;
        $this->mailer->MsgHTML($body);
        foreach ($mails as $mail) {
            $this->mailer->AddAddress($mail);
        }

        return $this->mailer->send();
    }
}
