<?php

namespace App\Frontend\Modules\User\Form\FormBuilder;

use OCFram\FormBuilder;
use OCFram\StringField;
use SFram\CsrfTokenManager;

/**
 * Class RegisterFormBuilder.php
 * @package App\Frontend\Modules\User\Form\FormBuilder
 */
class RegisterFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function build()
    {
        $this->form
            ->setAjax(true)
            ->setId('register-form')
            ->setAction(ROOT_API . '/user/register')
            ->add(
                new StringField(
                    [
                        'label' => 'Email',
                        'name' => 'email',
                        'required' => true,
                        'type' => 'email'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Mot de passe',
                        'name' => 'password',
                        'required' => true,
                        'type' => 'password'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Répeter Mot de passe',
                        'name' => 'password-repeat',
                        'required' => true,
                        'type' => 'password'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'name' => 'token',
                        'hidden' => 'hidden',
                        'value' => CsrfTokenManager::get()
                    ]
                )
            );
    }
}
