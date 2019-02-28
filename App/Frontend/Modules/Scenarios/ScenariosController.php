<?php

namespace App\Frontend\Modules\Scenarios;

use App\Backend\Modules\Scenarios\ScenariosController as ScenariosBackController;
use Entity\Scenario;
use FormBuilder\ScenariosFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class ScenariosController
 * @package App\Frontend\Modules\Scenarios
 */
class ScenariosController extends ScenariosBackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $scenarios = parent::executeIndex($request);

        $this->page->addVar('title', 'Gestion des scenarios');

        $cards = [];
        $cards[] = $this->makeScenariosWidget($scenarios);
        $addScenarioFab = new FloatingActionButton([
            'id' => "addScenarioFab",
            'fixed' => true,
            'icon' => "add",
            'href' => "../activapi.fr/scenarios-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addScenarioFab', $addScenarioFab);

    }

    /**
     * @param $senarios
     * @return \Materialize\Card\Card
     */
    public function makeScenariosWidget($senarios)
    {
        $domId = 'Scenarios';

        $scenarios = json_decode(json_encode($senarios), true);
        $scenariosData = [];

        foreach ($scenarios as $scenario) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link('', "../activapi.fr/scenarios-edit-" . $scenario["scenarioid"], 'edit', 'primaryTextColor');
            $linkDelete = new Link('', "../activapi.fr/scenarios-delete-" . $scenario["scenarioid"], 'delete', 'secondaryTextColor');
            $scenario["editer"] = $linkEdit->getHtmlForTable();
            $scenario["supprimer"] = $linkDelete->getHtmlForTable();

            $scenariosData[] = $scenario;
        }

        $hideColumns = ['data'];

        $table = WidgetFactory::makeTable($domId, $scenariosData, true, $hideColumns);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Scenarios');

        $domId = 'Suppression';
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect('../activapi.fr/scenarios');
            }
        }

        $link = new Link(
            $domId,
            "../activapi.fr/scenarios",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression du Scenario");
        $this->page->addVar('card', $card);

    }

    /**
     * @param HTTPRequest $request
     */
    public function executeEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Scenarios');
        $actionneursManager = $this->managers->getManagerOf('Actionneurs');
        $actionneurs = $actionneursManager->getList();
        $domId = 'Edition';

        if ($request->method() == 'POST') {
            $item = new Scenario([
                'nom' => $request->postData('nom'),
                'scenarioid' => $request->postData('scenarioid'),
                'actionneurid' => $request->postData('actionneurid'),
                'etat' => $request->postData('etat')
            ]);

            if ($request->getExists('scenarioid')) {
                $id = $request->getData('scenarioid');
                $item->setId($id);
            }

        } else {
            if ($request->getExists('scenarioid')) {
                $id = $request->getData("scenarioid");
                $item = $manager->getScenario($id);
            } else {
                $domId = 'Ajout';
                $item = new Scenario();

            }
        }

        $item->actionneurs = $actionneurs;

        $cards = [];

        $tmfb = new ScenariosFormBuilder($item);
        $tmfb->build();
        $form = $tmfb->form();

        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect('../activapi.fr/scenarios');
        }

        $link = new Link($domId,
            "../activapi.fr/scenarios",
            "arrow_back",
            "white-text",
            "white-text");

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));
        $cards[] = $card;

        $this->page->addVar('title', "Edition du Scénario");
        $this->page->addVar('cards', $cards);
    }

    /**
     * @return false|string
     */
    public function deleteFormView()
    {
        return $this->getBlock(BLOCK . '/deleteFormView.phtml');
    }

    /**
     * @param $form
     * @return false|string
     */
    public function editFormView($form)
    {
        return $this->getBlock(BLOCK . '/editFormView.phtml', $form);
    }
}
