<?php

namespace App\Frontend\Modules\Home;

use App\Frontend\Modules\User\Form\FormBuilder\LoginFormBuilder;
use App\Frontend\Modules\User\Form\FormBuilder\RegisterFormBuilder;
use Entity\User\User;
use Materialize\WidgetFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class HomeController
 * @package App\Frontend\Modules\Home
 */
class HomeController extends BackController
{

    /** @var \Model\User\UsersManagerPDO */
    protected $manager;

    /**
     * UserController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('User\Users');
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'ActivAPI - Accueil');
        $cards = [];
        $homeWidget = $this->makeHomeWidget();

        $cards[] = $homeWidget;

        $this->page->addVar('cards', $cards);
    }

    /**
     * @return \Materialize\Card\Card
     * @throws \Exception
     */
    public function makeHomeWidget()
    {
        $domId = 'Accueil';
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->getHomeView());

        $formHtml = $this->manager->getAdminUser()
            ? $this->editFormView($this->createLoginForm())
            : $this->editFormView($this->createRegisterForm());

        $card->addContent($formHtml);

        return $card;
    }

    /**
     * @return false|string
     */
    public function getHomeView()
    {
        return $this->getBlock(__DIR__ . '/Block/homeView.phtml');
    }

    /**
     * @return \OCFram\Form
     */
    protected function createLoginForm()
    {
        $lfb = new LoginFormBuilder(new User());
        $lfb->build();

        return $lfb->form();
    }

    protected function createRegisterForm()
    {
        $lfb = new RegisterFormBuilder(new User());
        $lfb->build();

        return $lfb->form();
    }
}
