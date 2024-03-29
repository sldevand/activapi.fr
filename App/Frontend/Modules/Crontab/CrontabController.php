<?php

namespace App\Frontend\Modules\Crontab;

use Cron\CronExpression;
use Entity\Crontab\Crontab;
use FormBuilder\CrontabFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Icon\YesNoIconifier;
use Materialize\Link\BackLinkFactory;
use Materialize\Link\DeleteLinkFactory;
use Materialize\Link\EditLinkFactory;
use Materialize\WidgetFactory;
use Model\Scenario\ScenarioManagerPDOFactory;
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

    /** @var \Materialize\Icon\YesNoIconifier */
    protected $iconifier;

    /**
     * CrontabController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(
        Application $app,
        string $module,
        string $action
    ) {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Crontab\Crontab');
        $this->iconifier = new YesNoIconifier();
    }


    /**
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
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        $domId = 'Edition';
        $this->page->addVar('title', "Edition de la crontab");

        if ($request->method() === 'POST') {
            $expression = trim((string) $request->postData('expression'));
            $this->validateCronExpression($expression, $request);
            $item = new Crontab([
                'name' => $request->postData('name'),
                'expression' => $expression,
                'active' => $request->postData('active'),
                'executor' => $request->postData('executor'),
                'args' => $request->postData('args'),
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

        $form = $this->createCrontabFormBuilder($item)->build();
        $fh = new FormHandler($form, $this->manager, $request);
        if ($fh->process()) {
            $this->app->httpResponse()->redirect($this->getCrontabIndexUrl());
        }

        $link = BackLinkFactory::create($domId, $this->getCrontabIndexUrl());

        $cardTitle = $link->getHtml();

        $cards = [];
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
    }

    public function executeDelete(HTTPRequest $request)
    {
        $domId = 'Suppression';
        if ($request->method() === 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $this->manager->delete($id);
                $this->app->httpResponse()->redirect($this->getCrontabIndexUrl());
            }
        }

        $link = BackLinkFactory::create($domId, $this->getCrontabIndexUrl());
        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression de la Crontab");
        $this->page->addVar('card', $card);
    }

    /**
     * @return \Materialize\Card\Card
     */
    public function makeCrontabWidget(array $crontabList)
    {
        $domId = 'Crontab';

        $crontabList = json_decode(json_encode($crontabList, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        $crontabListData = [];

        foreach ($crontabList as $crontab) {
            $linkEdit = EditLinkFactory::create($this->baseAddress . "crontab-edit-" . $crontab["id"]);
            $linkDelete = DeleteLinkFactory::create($this->baseAddress . "crontab-delete-" . $crontab["id"]);
            $crontab["active"] = $this->iconifier->iconifyResult($crontab["active"]);
            $crontab["editer"] = $linkEdit->getHtmlForTable();
            $crontab["supprimer"] = $linkDelete->getHtmlForTable();
            $crontabListData[] = $crontab;
        }

        $table = WidgetFactory::makeTable($domId, $crontabListData);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($table->getHtml());

        return $card;
    }

    public function validateCronExpression(string $cronExpression, HTTPRequest $request)
    {
        try {
            CronExpression::factory($cronExpression);
        } catch (\InvalidArgumentException $exception) {
            $this->app->user()->setFlash($exception->getMessage());
            $this->app->httpResponse()->redirect($request->requestURI());
        }
    }

    /**
     * @return CrontabFormBuilder
     * @throws \Exception
     */
    protected function createCrontabFormBuilder(Crontab $item)
    {
        $scenariosManagerFactory = new ScenarioManagerPDOFactory();
        $scenariosManager = $scenariosManagerFactory->getScenariosManager();
        $scenarios = $scenariosManager->getAll(null, false);
        $scenariosOptions = [];
        foreach ($scenarios as $scenario) {
            $scenariosOptions['scenario-' . $scenario->id()] = $scenario->getNom();
        }

        $crontabExecutors = \Crontab\ClassFinder::getClasses();
        $crontabOptions = [];
        foreach ($crontabExecutors as $executor) {
            $explodedFqn = explode('\\', (string) $executor);
            $crontabOptions[$executor] = $explodedFqn[4] . ':' . $explodedFqn[6];
        }

        $ctfb = new CrontabFormBuilder($item);
        $ctfb->addData('scenarios', $scenariosOptions);
        $ctfb->addData('crontab', $crontabOptions);

        return $ctfb;
    }

    /**
     * @return string
     */
    protected function getCrontabIndexUrl()
    {
        return $this->baseAddress . 'crontab';
    }
}
