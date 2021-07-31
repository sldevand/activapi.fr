<?php

namespace App\Frontend\Modules\User\Form\FormBuilder;

use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class RegisterFormBuilder.php
 * @package App\Frontend\Modules\User\Form\FormBuilder
 */
class RegisterFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
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
                        'label' => 'Prénom',
                        'name' => 'firstName',
                        'required' => true,
                        'type' => 'text'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Nom',
                        'name' => 'lastName',
                        'required' => true,
                        'type' => 'text'
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
            );
    }
}
