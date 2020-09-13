<?php

namespace App\Backend\Modules\Mailer;

use Exception;
use Mailer\Helper\Config;
use Mailer\MailerFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class MailerController
 * @package App\Backend\Modules\Mailer
 */
class MailerController extends BackController
{
    /** @var \Mailer\Helper\Config */
    protected $configHelper;

    /** @var \PHPMailer\PHPMailer\PHPMailer */
    protected $mailer;

    /**
     * MailerController constructor.
     * @param \OCFram\Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->configHelper = new Config($app, $this->managers->getManagerOf('Configuration\Configuration'));
        $this->mailer = MailerFactory::create();
    }

    /**
     * @param HTTPRequest $request
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executeTest(HTTPRequest $request)
    {
        http_response_code(200);
        if (!$this->configHelper->getEnabled()) {
            return $this->page->addVar('data', ['error' => 'Mailer module is not enabled']);
        }

        if (!$mailAddress = $this->configHelper->getEmail()) {
            return $this->page->addVar('data', ['error' => 'No mail was configured in Mailer module']);
        }

        date_default_timezone_set('Europe/Paris');
        setlocale (LC_TIME, 'fr_FR.utf8','fra');

        $body = file_get_contents(BACKEND_TEMPLATES.'/Mailer/test.html');
        $body = str_replace('%date%', strftime("Envoyé le %A %d %B à %T"), $body);

        $this->mailer->Subject = "Test from Activapi platform";
        $this->mailer->MsgHTML($body);
        $this->mailer->AddAddress($mailAddress);

        try {
            $sent = $this->mailer->send();
        } catch (Exception $exception) {
            return $this->page->addVar('data', ['error' => $exception->getMessage()]);
        }

        $message = $sent
            ? 'The message was successfully sent!'
            : 'An error occured when email was sent';

        return $this->page()->addVar('data',  ['data' => $message]);
    }
}
