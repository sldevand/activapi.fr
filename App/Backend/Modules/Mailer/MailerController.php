<?php

namespace App\Backend\Modules\Mailer;

use Exception;
use Mailer\Helper\Config;
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

    /** @var Helper\MailSender */
    protected $mailSender;

    /**
     * MailerController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->configHelper = new Config($app, $this->managers->getManagerOf('Configuration\Configuration'));
        $this->mailSender = new Helper\MailSender($app);
    }

    /**
     * @param HTTPRequest $request
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executeTest(HTTPRequest $request)
    {
        try {
            http_response_code(200);
            $body = file_get_contents(BACKEND_TEMPLATES . '/Mailer/test.html');
            $body = str_replace('%date%', strftime("Envoyé le %A %d %B à %T"), $body);
            $sent = $this->mailSender->sendMail('Test from Activapi platform', $body);
        } catch (Exception $exception) {
            return $this->page->addVar('data', ['error' => $exception->getMessage()]);
        }

        $message = $sent
            ? 'The message was successfully sent!'
            : 'An error occured when email was sent';

        return $this->page()->addVar('data', ['data' => $message]);
    }
}
