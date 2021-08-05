<?php

namespace App\Frontend\Modules\User;

use App\Frontend\Modules\User\Form\FormBuilder\LoginFormBuilder;
use App\Frontend\Modules\User\Form\FormBuilder\RegisterFormBuilder;
use Entity\User\User;
use Exception;
use Materialize\WidgetFactory;
use Model\User\UsersManagerPDO;
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
    /** @var UsersManagerPDO */
    protected $manager;

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
        $this->manager = $this->managers->getManagerOf('User\Users');
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeLogin(HTTPRequest $request)
    {
        $domId = 'Login';
        if ($this->app()->user()->isAuthenticated()) {
            $this->app()->httpResponse()->redirect($this->baseAddress);
        }

        if (!$this->manager->getAdminUser()) {
            $this->app()->user()->setFlash("No admin user saved, please register");
            $this->app()->httpResponse()->redirect($this->baseAddress . 'user/register');
        }

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
        if ($this->app()->user()->isAuthenticated()) {
            $this->app()->httpResponse()->redirect($this->baseAddress);
        }
        if ($this->manager->getAdminUser()) {
            $this->app()->user()->setFlash("Impossible to register twice, please login with admin credentials.");
            $this->app()->httpResponse()->redirect($this->baseAddress . 'user/login');
        }
        $tmfb = new RegisterFormBuilder(new User());
        $tmfb->build();
        $form = $tmfb->form();
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->editFormView($form));
        $this->page->addVar('card', $card);
    }
}
