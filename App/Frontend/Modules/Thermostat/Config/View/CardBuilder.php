<?php

namespace App\Frontend\Modules\Thermostat\Config\View;

use Materialize\FormView;
use Materialize\WidgetFactory;
use OCFram\ApplicationComponent;
use OCFram\Form;

/**
 * Class CardBuilder
 * @package App\Frontend\Modules\Thermostat\Config\View
 */
class CardBuilder extends ApplicationComponent
{
    use FormView;

    /**
     * @param Form $form
     * @return \Materialize\Card\Card
     */
    public function build(Form $form)
    {
        return WidgetFactory::makeCard(
            'configuration-thermostat',
            'Thermostat',
            $this->editFormView($form)
        );
    }
}
