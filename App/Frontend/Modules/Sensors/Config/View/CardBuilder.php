<?php

namespace App\Frontend\Modules\Sensors\Config\View;

use App\Frontend\Modules\FormView;
use Materialize\WidgetFactory;
use OCFram\ApplicationComponent;
use OCFram\Form;

/**
 * Class CardBuilder
 * @package App\Frontend\Modules\Sensors\Config\View
 */
class CardBuilder extends ApplicationComponent
{
    use FormView;

    /**
     * @param \OCFram\Form $form
     * @return \Materialize\Card\Card
     */
    public function build(Form $form)
    {
        return WidgetFactory::makeCard('configuration-sensors', 'Sensors', $this->editFormView($form));
    }
}
