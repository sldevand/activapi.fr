<?php

namespace App\Frontend\Modules;

use Materialize\Button\FlatButton;

trait FormView
{

    /**
     * @return false|string
     */
    public function deleteFormView()
    {
        return $this->getBlock(BLOCK . '/deleteFormView.phtml');
    }

    /**
     * @param $form
     * @return false|string
     */
    public function editFormView($form)
    {
        $button = new FlatButton(
            [
                'id' => 'submit',
                'title' => 'Valider',
                'color' => 'primaryTextColor',
                'type' => 'submit',
                'icon' => 'check'
            ]
        );

        return $this->getBlock(BLOCK . '/editFormView.phtml', $form, $button);
    }
}
