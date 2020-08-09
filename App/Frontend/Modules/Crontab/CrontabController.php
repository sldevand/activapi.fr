<?php

namespace App\Frontend\Modules\Crontab;

use Entity\Crontab\Crontab;
use FormBuilder\CrontabFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use Model\Crontab\CrontabManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class CrontabController
 * @package App\Frontend\Modules\Crontab
 */
class CrontabController extends BackController
{
    /** @var \Model\Crontab\CrontabManagerPDO $manager */
    protected $manager;

    public function __construct(
        Application $app,
        string $module,
        string $action
    ) {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Crontab\Crontab');
    }


    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Crontab');

        $crontabList = $this->manager->getList();

        $cards[] = $this->makeCrontabWidget($crontabList);

        $addCrontabFab = new FloatingActionButton([
            'id' => "addCrontabFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "crontab-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addCrontabFab', $addCrontabFab);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        $domId = 'Edition';

        if ($request->method() === 'POST') {
            $item = new Crontab([
                'name' => $request->postData('name'),
                'expression' => $request->postData('expression'),
                'active' => $request->postData('active'),
                'executor' => $request->postData('executor')
            ]);

            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $item->setId($id);
            }
        } else {
            if ($request->getExists('id')) {
                $id = $request->getData("id");
                $item = $this->manager->getUnique($id);
            } else {
                $domId = 'Ajout';
                $item = new Crontab();
            }
        }

        $cards = [];

        $ctfb = new CrontabFormBuilder($item);
        $ctfb->build();
        $form = $ctfb->form();

        $fh = new FormHandler($form, $this->manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect($this->baseAddress . 'crontab');
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "crontab",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));
        $cards[] = $card;

        $this->page->addVar('title', "Edition de la crontab");
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $domId = 'Suppression';
        if ($request->method() === 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $this->manager->delete($id);
                $this->app->httpResponse()->redirect($this->baseAddress . 'crontab');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . 'crontab',
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression de la Crontab");
        $this->page->addVar('card', $card);
    }


    /**
     * @param array $crontabList
     * @return \Materialize\Card\Card
     */
    public function makeCrontabWidget(array $crontabList)
    {
        $domId = 'Crontab';

        $crontabList = json_decode(json_encode($crontabList), true);
        $crontabListData = [];

        foreach ($crontabList as $crontab) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link(
                '',
                $this->baseAddress . "crontab-edit-" . $crontab["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "crontab-delete-" . $crontab["id"],
                'delete',
                'secondaryTextColor'
            );
            $crontab["active"] = $this->iconifyResult($crontab["active"]);
            $crontab["editer"] = $linkEdit->getHtmlForTable();
            $crontab["supprimer"] = $linkDelete->getHtmlForTable();
            $crontabListData[] = $crontab;
        }

        $table = WidgetFactory::makeTable($domId, $crontabListData);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param $state
     * @return string
     */
    public function iconifyResult($state)
    {
        if ($state == 1) {
            $icon = "check";
            $color = "teal-text";
        } else {
            $icon = "cancel";
            $color = "secondaryTextColor";
        }

        $html = '<i class="material-icons ' . $color . ' ">' . $icon . '</i>';

        return $html;
    }
}
