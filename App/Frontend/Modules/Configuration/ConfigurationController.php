<?php

namespace App\Frontend\Modules\Configuration;

use App\Frontend\Modules\Configuration\Action\MailerConfigurationAction;
use App\Frontend\Modules\FormView;
use Helper\Configuration\Data;
use Mailer\Helper\Config;
use Materialize\Button\FlatButton;
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

    /** @var \App\Frontend\Modules\Configuration\Action\MailerConfigurationAction */
    protected $mailerConfigAction;

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
        $this->dataHelper = new Data($app);
        $this->mailerConfigAction = new MailerConfigurationAction(
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
        $mailerForm = $this->mailerConfigAction->execute($request);
        $mailerTestButton = $this->getBlock(__DIR__ . '/Block/mailerTestButton.phtml');
        $mailerCard = WidgetFactory::makeCard('configuration-mailer', 'Mailer', $this->editFormView($mailerForm));
        $mailerCard->addContent($mailerTestButton);

        $cards[] = $mailerCard;
        $this->page->addVar('cards', $cards);
    }
}
