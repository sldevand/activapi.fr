<?php

namespace App\Frontend\Modules\User;

use App\Frontend\Modules\User\Form\FormBuilder\LoginFormBuilder;
use Entity\User\User;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class UserController
 * @package App\Frontend\Modules\USer
 */
class UserController extends BackController
{
    /**
     * @param HTTPRequest $request
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
}
