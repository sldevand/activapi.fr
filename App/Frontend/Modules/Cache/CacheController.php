<?php

namespace App\Frontend\Modules\Cache;

use App\Backend\Modules\Cache\Command\Executor\Flush;
use App\Backend\Modules\Cache\Command\FlushCommand;
use Materialize\Button\FlatButton;
use Materialize\FormView;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class CacheController
 * @package App\Frontend\Modules\Cache
 */
class CacheController extends BackController
{
    use FormView;

    /**
     * @return int|void
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        if ($request->method() === 'POST') {
            $flushExecutor = new Flush();
            $files = $flushExecutor->execute($this->app());
            $message = $files ? 'Cache was flushed' : 'No cache to flush';
            $this->app()->user()->setFlash($message);
            $this->app->httpResponse()->redirect($this->baseAddress . 'cache');
            return;
        }
        $this->page->addVar('title', 'Gestion des caches');

        $card = WidgetFactory::makeCard('cacheCard', 'Caches');
        $submit = new FlatButton(
            [
                'id' => 'flush-all-caches',
                'title' => 'Flush all caches',
                'icon' => 'delete',
                'color' => 'secondaryTextColor',
                'type' => 'submit'
            ]
        );
        $form = $this->getBlock(__DIR__ . '/Block/flushAllForm.phtml', $submit, 'cache');
        $card->addContent($form);
        $cards = [$card];
        $this->page()->addVar('cards', $cards);
    }
}