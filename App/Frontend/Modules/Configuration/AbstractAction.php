<?php

namespace App\Frontend\Modules\Configuration;

use Api\Helper\Configuration\ConfigInterface;
use App\Frontend\Modules\Configuration\Api\ActionInterface;
use App\Frontend\Modules\Configuration\Form\ConfigurationFormHandler;
use Helper\Configuration\Config;
use Helper\Configuration\Data;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\ApplicationComponent;
use OCFram\Form;
use OCFram\HTTPRequest;

/**
 * Class AbstractAction
 * @package App\Frontend\Modules\Configuration
 */
abstract class AbstractAction  extends ApplicationComponent implements ActionInterface
{
    /** @var string */
    public const HELPER_CLASS = '\\Helper\\Configuration\\Config';

    /** @var \Model\Configuration\ConfigurationManagerPDO */
    protected $manager;

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
     */
    public function __construct(
        Application $app,
        ConfigurationManagerPDO $manager,
        Data $dataHelper
    ) {
        parent::__construct($app);

        $this->manager = $manager;
        $this->dataHelper = $dataHelper;
        $this->configHelper = new Config($app, $manager);
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

            var_dump($request->postData($key));
            echo '</BR>';

            $fh = new ConfigurationFormHandler($form, $this->manager, $request, $configuration);
            if ($fh->process()) {
                $processed++;
            }
        }
        die;
        $message = $processed === count($configurations)
            ? $this->messages['success']
            : $this->messages['error'];

        $this->app()->user()->setFlash($message);
        $this->app->httpResponse()->redirect($this->dataHelper->getConfigurationIndexUrl());
    }
}
