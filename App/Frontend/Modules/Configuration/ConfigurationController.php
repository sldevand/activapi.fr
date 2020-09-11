<?php

namespace App\Frontend\Modules\Configuration;

use App\Frontend\Modules\FormView;
use Entity\Configuration\Configuration;
use FormBuilder\Configuration\ConfigurationFormBuilder;
use Materialize\WidgetFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\FormHandler;
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
        $this->page->addVar('title', 'Gestion des actions');

        $cards = [];
        $cards[] = $this->makeEmailCard($request);
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param HTTPRequest $request
     * @return \Materialize\Card\Card
     */
    protected function makeEmailCard(HTTPRequest $request)
    {
        $card = WidgetFactory::makeCard('configuration-email', 'Email');

        $cfb = new ConfigurationFormBuilder(new Configuration());
        $cfb->build();
        $form = $cfb->form();
        $fh = new FormHandler($form, $this->manager, $request);
        if ($fh->process()) {
            $this->app->httpResponse()->redirect($this->getConfigurationIndexUrl());
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
