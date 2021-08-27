<?php

namespace App\Frontend\Modules\Sensors\Config;

use App\Frontend\Modules\Configuration\AbstractAction;
use App\Frontend\Modules\Sensors\Config\Form\ConfigurationFormBuilder;
use App\Frontend\Modules\Sensors\Config\View\CardBuilder;
use Entity\Configuration\ConfigurationFactory;
use Helper\Configuration\Data;
use OCFram\Managers;
use Sensors\Helper\Config;
use Model\Configuration\ConfigurationManagerPDO;
use OCFram\Application;
use OCFram\Form;
use OCFram\HTTPRequest;

/**
 * Class Action
 * @package App\Frontend\Modules\Sensors\Config
 */
class Action extends AbstractAction
{
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
            $encodedValue = json_encode($request->postData(Config::PATH_SENSORS_ALERT_TIMES));
            $request->setPostData(Config::PATH_SENSORS_ALERT_TIMES,$encodedValue);
            $this->doPost($configurations, $form, $request);
        }

        $cardBuilder = new CardBuilder($this->app());

        return $cardBuilder->build($form);
    }

    /**
     * @param array $configurations
     * @return \OCFram\Form
     * @throws \Exception
     */
    protected function createForm(array $configurations): Form
    {
        $cfb = new ConfigurationFormBuilder(ConfigurationFactory::create());
        $cfb->setData($configurations);
        /** @var \Model\SensorsManagerPDO $sensorsManager */
        $sensorsManager = $this->managers->getManagerOf('Sensors');
        $cfb->addData('sensors', $sensorsManager->getList());
        $cfb->build();

        return $cfb->form();
    }
}
