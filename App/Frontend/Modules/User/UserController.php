<?php

namespace App\Frontend\Modules\User;

use App\Frontend\Modules\User\Form\FormBuilder\LoginFormBuilder;
use App\Frontend\Modules\User\Form\FormBuilder\RegisterFormBuilder;
use Entity\User\User;
use Materialize\WidgetFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\CsrfTokenManager;

/**
 * Class UserController
 * @package App\Frontend\Modules\USer
 */
class UserController extends BackController
{
    /**
     * UserController constructor.
     * @param \OCFram\Application $app
     * @param $module
     * @param $action
     * @throws \Exception
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        CsrfTokenManager::generate();
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeLogin(HTTPRequest $request)
    {
        $domId = 'Login';

        $tmfb = new LoginFormBuilder(new User());
        $tmfb->build();
        $form = $tmfb->form();
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->editFormView($form));
        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeRegister(HTTPRequest $request)
    {
        $domId = 'Register';

        $tmfb = new RegisterFormBuilder(new User());
        $tmfb->build();
        $form = $tmfb->form();
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->editFormView($form));
        $this->page->addVar('card', $card);
    }
}
