<?php

namespace App\Frontend\Modules\Thermostat\Config;

use App\Frontend\Modules\Configuration\AbstractAction;
use App\Frontend\Modules\Thermostat\Config\Form\ConfigurationFormBuilder;
use App\Frontend\Modules\Thermostat\Config\View\CardBuilder;
use Entity\Configuration\ConfigurationFactory;
use Helper\Configuration\Data;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\Form;
use OCFram\HTTPRequest;
use OCFram\Managers;
use Thermostat\Helper\Config;

/**
 * Class Action
 * @package App\Frontend\Modules\Thermostat\Config
 */
class Action extends AbstractAction
{
    /**
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
        parent::__construct($app, $manager, $dataHelper, $managers);
        $this->configHelper = new Config($app, $manager);
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @return \Materialize\Card\Card
     * @throws \Exception
     */
    public function execute(HTTPRequest $request)
    {
        $configurations = $this->configHelper->getConfigurations();
        $form = $this->createForm($configurations);

        if (
            $request->method() === HTTPRequest::POST
            && $request->postData(ConfigurationFormBuilder::NAME) === ConfigurationFormBuilder::NAME
        ) {
            $this->doPost($configurations, $form, $request);
        }

        $cardBuilder = new CardBuilder($this->app());

        return $cardBuilder->build($form);
    }

    /**
     * @param array $configurations
     * @return \OCFram\Form
     */
    protected function createForm(array $configurations): Form
    {
        $cfb = new ConfigurationFormBuilder(ConfigurationFactory::create());
        $cfb->setData($configurations);
        $cfb->build();

        return $cfb->form();
    }
}
