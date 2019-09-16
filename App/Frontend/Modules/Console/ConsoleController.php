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

        $commandDomainAddress = $this->app()->config()->get("commandDomainAddress");
        $period = $request->getData('period');
        $url = $commandDomainAddress . "/log/$period";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $logs = json_decode(curl_exec($ch), true);
        $card = WidgetFactory::makeCard('console-card', 'Console');
        $card->addContent($this->nodeView($switchButton));
        $card->addContent($this->commandView($sendButton));
        $card->addContent($this->displayView($logs['messages']));
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
     * @param $log
     * @return false|string
     */
    public function displayView($logs)
    {
        return $this->getBlock(MODULES . '/Console/Block/displayView.phtml', $logs);
    }
}
