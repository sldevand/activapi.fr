<?php

namespace App\Frontend\Modules\Mailer\Config;

use App\Frontend\Modules\Configuration\Form\FormBuilder\EmailConfigurationFormBuilder;
use App\Frontend\Modules\Configuration\Form\FormHandler\ConfigurationFormHandler;
use Entity\Configuration\ConfigurationFactory;
use Helper\Configuration\Data;
use Mailer\Helper\Config;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\ApplicationComponent;
use OCFram\Form;
use OCFram\HTTPRequest;

/**
 * Class Action
 * @package App\Frontend\Modules\Mailer\Config
 * @author Synolia <contact@synolia.com>
 */
class Action extends ApplicationComponent
{
    /** @var \Model\Configuration\ConfigurationManagerPDO */
    protected $manager;

    /** @var \Helper\Configuration\Data */
    protected $dataHelper;

    /** @var \Mailer\Helper\Config */
    protected $configHelper;

    /**
     * Action constructor.
     * @param \OCFram\Application $app
     * @param \Model\Configuration\ConfigurationManagerPDO $manager
     * @param \Helper\Configuration\Data $dataHelper
     * @param \Mailer\Helper\Config $configHelper
     */
    public function __construct(
        Application $app,
        ConfigurationManagerPDO $manager,
        Data $dataHelper,
        Config $configHelper
    ) {
        parent::__construct($app);

        $this->manager = $manager;
        $this->dataHelper = $dataHelper;
        $this->configHelper = $configHelper;
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @return \OCFram\Form
     * @throws \Exception
     */
    public function execute(HTTPRequest $request)
    {
        $configurations = $this->configHelper->getConfigurations();
        $mailerForm = $this->createMailerForm($configurations);

        if (
            $request->method() === 'POST'
            && $request->postData(EmailConfigurationFormBuilder::NAME) === EmailConfigurationFormBuilder::NAME
        ) {
            $this->doPost($configurations, $mailerForm, $request);
        }

        return $mailerForm;
    }

    /**
     * @param \Entity\Configuration\Configuration[] $configurations
     * @param \OCFram\Form $form
     * @param \OCFram\HTTPRequest $request
     * @throws \Exception
     */
    protected function doPost(array $configurations, Form $form, HTTPRequest $request)
    {
        $processed = 0;
        foreach ($configurations as $key => $configuration) {
            if (!$requestConfigValue = $request->postData($key)) {
                $this->app()->user()->setFlash("Missing $key parameter in post");
                $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
                return;
            }

            $configuration->setConfigKey($key);
            $configuration->setConfigValue($requestConfigValue);

            $fh = new ConfigurationFormHandler($form, $this->manager, $request, $configuration);
            if ($fh->process()) {
                $processed++;
            }
        }

        $message = $processed === count($configurations)
            ? 'Mailer configuration have been saved!'
            : 'Mailer configuration could not been saved!';

        $this->app()->user()->setFlash($message);
        $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
    }

    /**
     * @param array $configurations
     * @return \OCFram\Form
     */
    protected function createMailerForm(array $configurations)
    {
        $cfb = new EmailConfigurationFormBuilder(ConfigurationFactory::create());
        $cfb->setData($configurations);
        $cfb->build();

        return $cfb->form();
    }
}
