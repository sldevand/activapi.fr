<?php

namespace App\Frontend\Modules\Configuration;

use App\Frontend\Modules\FormView;
use App\Frontend\Modules\Mailer\Config\Action;
use App\Frontend\Modules\Mailer\Config\View\CardBuilder;
use Helper\Configuration\Data;
use Mailer\Helper\Config;
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

    /** @var \Helper\Configuration\Data */
    protected $dataHelper;

    /** @var \App\Frontend\Modules\Mailer\Config\Action */
    protected $mailerConfigAction;

    /** @var \App\Frontend\Modules\Mailer\Config\View\CardBuilder */
    protected $cardBuilder;

    /**
     * ConfigurationController constructor.
     * @param \OCFram\Application $app
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
        $this->dataHelper = new Data($app);
        $this->cardBuilder = new CardBuilder($app);
        $this->mailerConfigAction = new Action(
            $this->app(),
            $this->manager,
            $this->dataHelper,
            new Config($app, $this->manager)
        );
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Configuration Système');

        $cards = [];
        // foreach modules (Frontend App) check if dir Config
            // get the card corresponding to config
            // add it to $cards[]
        //endforeach

        //Start Replace this card
        $mailerForm = $this->mailerConfigAction->execute($request);

        //Start Replace this card

        $cards[] = $this->cardBuilder->build($mailerForm);
        $this->page->addVar('cards', $cards);
    }
}
