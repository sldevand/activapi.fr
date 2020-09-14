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
        $this->form->add(
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
        );
    }
}
