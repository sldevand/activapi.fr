<?php

namespace App\Frontend\Modules\Home;

use App\Frontend\Modules\User\Form\FormBuilder\LoginFormBuilder;
use Entity\User\User;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class HomeController
 * @package App\Frontend\Modules\Home
 */
class HomeController extends BackController
{
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
     */
    public function makeHomeWidget()
    {
        $domId = 'Accueil';
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->getHomeView());
        $card->addContent($this->editFormView($this->createLoginForm()));

        return $card;
    }

    /**
     * @return false|string
     */
    public function getHomeView()
    {
        $loginForm = $this->createLoginForm();
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
}
