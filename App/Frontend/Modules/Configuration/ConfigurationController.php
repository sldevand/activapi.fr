<?php

namespace App\Frontend\Modules\Configuration;

use App\Frontend\Modules\FormView;
use Entity\Configuration\Configuration;
use FormBuilder\Configuration\EmailConfigurationFormBuilder;
use FormHandler\Configuration\ConfigurationFormHandler;
use Materialize\WidgetFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ConfigurationController
 * @package App\Frontend\Modules\Configuration
 */
class ConfigurationController extends BackController
{
    use FormView;

    /** @var \Model\Configuration\ConfigurationManagerPDO $manager */
    protected $manager;

    /**
     * CrontabController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(
        Application $app,
        string $module,
        string $action
    ) {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Configuration\Configuration');
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Configuration Système');

        $cards = [];
        $cards[] = $this->makeEmailCard($request);
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @return \Materialize\Card\Card
     * @throws \Exception
     */
    protected function makeEmailCard(HTTPRequest $request)
    {
        $card = WidgetFactory::makeCard('configuration-email', 'Email');
        $configuration = $this->manager->getUniqueBy('configKey', 'email');

        if (!$configuration ) {
            $configuration = new Configuration(
                [
                    'configKey' => 'email/email',
                    'configValue' => ''
                ]
            );
        }

        $cfb = new EmailConfigurationFormBuilder($configuration);
        $cfb->setData(
            [
                'id'    => $configuration->id(),
                'email' => $configuration->getConfigValue()
            ]
        );
        $cfb->build();
        $form = $cfb->form();

        if ($request->postData('email')) {
            $configuration->setConfigValue($request->postData('email'));
            $fh = new ConfigurationFormHandler($form, $this->manager, $request, $configuration);
            if ($fh->process()) {
                $this->app->httpResponse()->redirect($this->getConfigurationIndexUrl());
            }
        }


        $card->addContent($this->editFormView($form));

        return $card;
    }

    /**
     * @return string
     */
    protected function getConfigurationIndexUrl()
    {
        return $this->baseAddress . 'configuration';
    }
}
