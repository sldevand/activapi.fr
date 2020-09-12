<?php

namespace App\Frontend\Modules\Configuration\Action;

use App\Frontend\Modules\Configuration\Form\FormBuilder\EmailConfigurationFormBuilder;
use App\Frontend\Modules\Configuration\Form\FormHandler\ConfigurationFormHandler;
use Entity\Configuration\Configuration;
use Entity\Configuration\ConfigurationFactory;
use Helper\Configuration\Config;
use Helper\Configuration\Data;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\ApplicationComponent;
use OCFram\Form;
use OCFram\HTTPRequest;

/**
 * Class MailerConfigurationAction
 * @package App\Frontend\Modules\Configuration\Action
 */
class MailerConfigurationAction extends ApplicationComponent
{
    /** @var \Model\Configuration\ConfigurationManagerPDO */
    protected $manager;

    /** @var \Helper\Configuration\Data */
    protected $dataHelper;

    /**
     * MailerConfigurationAction constructor.
     * @param \OCFram\Application $app
     * @param \Model\Configuration\ConfigurationManagerPDO $manager
     * @param \Helper\Configuration\Data $dataHelper
     */
    public function __construct(
        Application $app,
        ConfigurationManagerPDO $manager,
        Data $dataHelper
    ) {
        parent::__construct($app);

        $this->manager = $manager;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @return \OCFram\Form
     * @throws \Exception
     */
    public function execute(HTTPRequest $request)
    {
        $mailerConfiguration = $this->getMailerConfiguration();
        $mailerForm = $this->createMailerForm($mailerConfiguration);
        $this->doMailerPost($mailerConfiguration, $mailerForm, $request);

        return $mailerForm;
    }

    /**
     * @param \Entity\Configuration\Configuration $configuration
     * @param \OCFram\Form $form
     * @param \OCFram\HTTPRequest $request
     * @throws \Exception
     */
    protected function doMailerPost(Configuration $configuration, Form $form, HTTPRequest $request)
    {
        if ($mailerAlertEmail = $request->postData(Config::PATH_MAILER_ALERT_EMAIL)) {
            $configuration->setConfigValue($mailerAlertEmail);
            $fh = new ConfigurationFormHandler($form, $this->manager, $request, $configuration);
            if ($fh->process()) {
                $this->app()->user()->setFlash('Mailer configuration have been saved!');
                $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
            }
        }
    }

    /**
     * @param \Entity\Configuration\Configuration $configuration
     * @return \OCFram\Form
     */
    protected function createMailerForm(Configuration $configuration)
    {
        $cfb = new EmailConfigurationFormBuilder($configuration);
        $cfb->setData(
            [
                'id' => $configuration->id(),
                Config::PATH_MAILER_ALERT_EMAIL => $configuration->getConfigValue()
            ]
        );
        $cfb->build();

        return $cfb->form();
    }

    /**
     * @return \Entity\Configuration\Configuration|null|\OCFram\Entity
     * @throws \Exception
     */
    protected function getMailerConfiguration()
    {
        if (!$configuration = $this->manager->getUniqueBy('configKey', Config::PATH_MAILER_ALERT_EMAIL)) {
            $configuration = ConfigurationFactory::create(
                [
                    'configKey' => Config::PATH_MAILER_ALERT_EMAIL,
                    'configValue' => ''
                ]
            );
        }

        return $configuration;
    }
}
