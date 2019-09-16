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
    /**
     * @param HTTPRequest $request
     */
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

        $period = $request->getData('period');

        $card = WidgetFactory::makeCard('console-card', 'Console');
        $card->addContent($this->nodeView($switchButton));
        $card->addContent($this->commandView($sendButton));
        $card->addContent($this->displayView($period));
        $cards = [];
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('sendButton', $sendButton);
    }

    /**
     * @param $switchButton
     * @return false|string
     */
    public function nodeView($switchButton)
    {
        return $this->getBlock(MODULES . '/Console/Block/nodeView.phtml', $switchButton);
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
