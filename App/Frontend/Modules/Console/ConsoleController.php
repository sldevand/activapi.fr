<?php

namespace App\Frontend\Modules\Console;

use Materialize\Button\FlatButton;
use Materialize\Button\SwitchButton;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ConsoleController
 * @package App\Frontend\Modules\Console
 */
class ConsoleController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Console DomusBox');

        $sendButton = new FlatButton(
            [
                'id' => 'send-command',
                'title' => 'Envoyer',
                'icon' => 'send',
                'color' => 'primaryTextColor',
                'type' => 'button'
            ]
        );

        $switchButton = new SwitchButton(
            [
                'id' => 'node',
                'title' => 'Node Server'
            ]
        );

        $serialportResetButton = new FlatButton(
            [
                'id'    => 'serialport-reset',
                'title' => '',
                'icon'  => 'autorenew',
                'color' => 'primaryTextColor',
                'type'  => 'button'
            ]
        );

        $period = $request->getData('period');

        $card = WidgetFactory::makeCard('console-card', 'Console');
        $card->addContent($this->nodeView($switchButton, $serialportResetButton));
        $card->addContent($this->commandView($sendButton));
        $card->addContent($this->displayView($period));
        $cards = [];
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('sendButton', $sendButton);
    }

    /**
     * @return false|string
     */
    public function nodeView(SwitchButton $switchButton, FlatButton $serialportResetButton)
    {
        return $this->getBlock(
            MODULES . '/Console/Block/nodeView.phtml',
            $switchButton,
            $serialportResetButton
        );
    }

    /**
     * @param $sendButton
     * @return false|string
     */
    public function commandView($sendButton)
    {
        return $this->getBlock(MODULES . '/Console/Block/commandView.phtml', $sendButton);
    }

    /**
     * @param string $period
     * @return false|string
     */
    public function displayView($period)
    {
        return $this->getBlock(MODULES . '/Console/Block/displayView.phtml', $period);
    }
}
