<?php

namespace App\Frontend\Modules\Configuration;

use App\Frontend\Modules\Configuration\Api\ActionInterface;
use App\Frontend\Modules\Configuration\Form\ConfigurationFormHandler;
use Helper\Configuration\Config;
use Helper\Configuration\Data;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\ApplicationComponent;
use OCFram\Form;
use OCFram\HTTPRequest;
use OCFram\Managers;

/**
 * Class AbstractAction
 * @package App\Frontend\Modules\Configuration
 */
abstract class AbstractAction  extends ApplicationComponent implements ActionInterface
{
    /** @var string */
    final public const HELPER_CLASS = '\\Helper\\Configuration\\Config';

    /** @var \Model\Configuration\ConfigurationManagerPDO */
    protected $manager;

    /** @var \OCFram\Managers */
    protected $managers;

    /** @var \Helper\Configuration\Data */
    protected $dataHelper;

    /** @var \Api\Helper\Configuration\ConfigInterface */
    protected $configHelper;

    /**
     * @var string[]
     */
    protected $messages = [
        'success' => 'Configuration have been saved!',
        'error'   => 'Configuration could not been saved!'
    ];

    /**
     * Action constructor.
     * @param \OCFram\Application $app
     * @param \Model\Configuration\ConfigurationManagerPDO $manager
     * @param \Helper\Configuration\Data $dataHelper
     * @param \OCFram\Managers $managers
     */
    public function __construct(
        Application $app,
        ConfigurationManagerPDO $manager,
        Data $dataHelper,
        Managers $managers
    ) {
        parent::__construct($app);

        $this->manager = $manager;
        $this->dataHelper = $dataHelper;
        $this->configHelper = new Config($app, $manager);
        $this->managers = $managers;
    }

    /**
     * @return $this
     */
    protected function beforeDoPost(array &$configurations, Form &$form, HTTPRequest &$request) {
        return $this;
    }

    /**
     * @param \Entity\Configuration\Configuration[] $configurations
     * @throws \Exception
     */
    protected function doPost(array $configurations, Form $form, HTTPRequest $request)
    {
        $this->beforeDoPost($configurations, $form,$request);
        $processed = 0;
        foreach ($configurations as $key => $configuration) {
            if (!$request->postData($key) && $request->postData($key) !== '') {
                $this->app()->user()->setFlash("Missing $key parameter in post");
                $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
                return;
            }

            $configuration->setConfigKey($key);
            $configuration->setConfigValue($request->postData($key));

            $fh = new ConfigurationFormHandler($form, $this->manager, $request, $configuration);
            if ($fh->process()) {
                $processed++;
            }
        }

        $message = $processed === count($configurations)
            ? $this->messages['success']
            : $this->messages['error'];

        $this->app()->user()->setFlash($message);
        $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
    }
}
