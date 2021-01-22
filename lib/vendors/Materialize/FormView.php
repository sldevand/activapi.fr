<?php

namespace Materialize;

use Materialize\Button\FlatButton;
use OCFram\Block;

/**
 * Trait FormView
 * @package Materialize
 */
trait FormView
{
    /**
     * @return false|string
     */
    public function deleteFormView()
    {
        return Block::getTemplate(BLOCK . '/deleteFormView.phtml');
    }

    /**
     * @param $form
     * @return false|string
     */
    public function editFormView($form)
    {
        $button = new FlatButton(
            [
                'id' => 'submit-'. time(),
                'title' => 'Valider',
                'color' => 'primaryTextColor',
                'type' => 'submit',
                'icon' => 'check',
                'wrapper' => 'col s12'
            ]
        );

        return Block::getTemplate(BLOCK . '/editFormView.phtml', $form, $button);
    }
}
