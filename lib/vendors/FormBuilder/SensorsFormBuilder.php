<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class SensorsFormBuilder
 * @package FormBuilder
 */
class SensorsFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $this->form
            ->add(
                new StringField([
                    'label' => 'radioid',
                    'name' => 'radioid',
                    'required' => true
                ])
            )->add(
                new StringField([
                    'label' => 'nom',
                    'name' => 'nom',
                    'required' => true
                ])
            )->add(
                new StringField([
                    'label' => 'categorie',
                    'name' => 'categorie',
                    'required' => true
                ])
            )->add(
                new StringField([
                    'label' => 'radioaddress',
                    'name' => 'radioaddress',
                    'required' => true
                ])
            );
    }
}
