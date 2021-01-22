<?php

namespace App\Frontend\Modules\Mailer\Config\View;

use Materialize\FormView;
use Materialize\Button\FlatButton;
use Materialize\WidgetFactory;
use OCFram\ApplicationComponent;
use OCFram\Block;
use OCFram\Form;

/**
 * Class CardBuilder
 * @package App\Frontend\Modules\Mailer\Config\View
 */
class CardBuilder extends ApplicationComponent
{
    const TEST_BUTTON_TEMPLATE = __DIR__ . '/../Block/mailerTestButton.phtml';

    use FormView;

    /**
     * @param $mailerForm
     * @return \Materialize\Card\Card
     * @throws \Exception
     */
    public function build(Form $mailerForm)
    {
        $baseAddress = $this->app()->config()->getEnv('BASE_URL');
        $mailerTestButton = Block::getTemplate(
            self::TEST_BUTTON_TEMPLATE,
            $baseAddress . 'api/mailer/test',
            $this->makeTestButton()
        );
        $mailerCard = WidgetFactory::makeCard('configuration-mailer', 'Mailer', $this->editFormView($mailerForm));

        return $mailerCard->addContent($mailerTestButton);
    }

    /**
     * @return \Materialize\Button\FlatButton
     */
    protected function makeTestButton(): FlatButton
    {
        return new FlatButton(
            [
                'id' => 'mailer-alert-test-button',
                'title' => "Envoyer email de test",
                'color' => 'secondaryTextColor',
                'icon' => 'send',
                'wrapper' => 'col s12'
            ]
        );
    }
}
