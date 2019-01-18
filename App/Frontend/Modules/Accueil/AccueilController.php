<?php

namespace App\Frontend\Modules\Accueil;

use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class AccueilController
 * @package App\Frontend\Modules\Accueil
 */
class AccueilController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'ActivAPI - Accueil');
        $cards = [];
        $cards[] = $this->makeAccueilWidget();

        $this->page->addVar('cards', $cards);
    }

    /**
     * @return \Materialize\Card
     */
    public function makeAccueilWidget()
    {
        $domId = 'Accueil';

        $cardContent = '<div class="row"> 
                          <div class="col s6 valign-wrapper">
                            <i class="valign material-icons left">build</i>      
                            <div class="valign ">Bienvenue sur ActivAPI, ici, vous pouvez administrer votre solution ActivHome</div>   
                          </div>       
                      </div>';
        return WidgetFactory::makeCard($domId, $domId, $cardContent);
    }
}
