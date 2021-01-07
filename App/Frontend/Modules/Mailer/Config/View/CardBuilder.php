<?php

namespace App\Frontend\Modules\Mailer\Config\View;

use App\Frontend\Modules\FormView;
use Materialize\WidgetFactory;
use OCFram\ApplicationComponent;
use OCFram\Block;
use OCFram\Form;

class CardBuilder extends ApplicationComponent
{
    use FormView;

    /**
     * @param $mailerForm
     * @return \Materialize\Card\Card
     * @throws \Exception
     */
    public function build(Form $mailerForm) {
        $baseAddress = $this->app()->config()->getEnv('BASE_URL');
        $mailerTestButtonUrl = $baseAddress . 'api/mailer/test';
        $mailerTestButton = Block::getBlock(__DIR__ . '/Block/mailerTestButton.phtml', $mailerTestButtonUrl);
        $mailerCard = WidgetFactory::makeCard('configuration-mailer', 'Mailer', $this->editFormView($mailerForm));

        return $mailerCard->addContent($mailerTestButton);
    }


}