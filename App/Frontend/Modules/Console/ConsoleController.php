<?php

namespace App\Frontend\Modules\Console;

use Materialize\Button\FlatButton;
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

        $sendButton = new FlatButton([
            'id' => 'send-command',
            'title' => 'Envoyer',
            'icon' => 'send',
            'color' => 'primaryTextColor',
            'type' => 'button'
        ]);

        $address = 'localhost';
        $port = 5901;

        $url = "http://$address/dashboard/resultat.php?log";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $log = curl_exec($ch);

        $card = WidgetFactory::makeCard('console-card', 'Console');
        $card->addContent($this->commandView($sendButton));
        $card->addContent($this->displayView($log));


        $cards = [];
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('sendButton', $sendButton);
        $this->page->addVar('log', $log);
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
    public function displayView($log)
    {
        return $this->getBlock(MODULES . '/Console/Block/displayView.phtml', $log);
    }
}
