<?php

namespace App\Frontend\Modules\Scenarios;

use App\Backend\Modules\Scenarios\ScenariosController as ScenariosBackController;
use App\Frontend\Modules\FormView;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use OCFram\HTTPRequest;

/**
 * Class ScenariosController
 * @package App\Frontend\Modules\Scenarios
 */
class ScenariosController extends ScenariosBackController
{
    use FormView;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $scenarios = parent::executeGet($request);

        $this->page->addVar('title', 'Gestion des scenarios');

        $cards = [];
        $cards[] = $this->makeScenariosWidget($scenarios);
        $addScenarioFab = new FloatingActionButton([
            'id' => "addScenarioFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "scenarios-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addScenarioFab', $addScenarioFab);
    }

    /**
     * @param array $scenarios
     * @return \Materialize\Card\Card
     */
    public function makeScenariosWidget($scenarios)
    {
        $domId = 'Scenarios';
        $scenarios = json_decode(json_encode($scenarios), true);
        $card = WidgetFactory::makeCard($domId, $domId);
        if (!$scenarios) {
            $card->addContent('Pas de scenarios');
            return $card;
        }
        $table = $this->createScenariosTable($scenarios, 'scenarios-table');
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param array $scenarios
     * @param string $domId
     * @return \Materialize\Table
     */
    public function createScenariosTable($scenarios, $domId)
    {
        $scenariosData = [];
        foreach ($scenarios as $scenario) {
            $linkEdit = new Link(
                '',
                $this->baseAddress . "scenarios-edit-" . $scenario["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "scenarios-delete-" . $scenario["id"],
                'delete',
                'secondaryTextColor'
            );
            $scenario["editer"] = $linkEdit->getHtmlForTable();
            $scenario["supprimer"] = $linkDelete->getHtmlForTable();
            $scenariosData[] = $scenario;
        }

        $hideColumns = ['data', 'sequences'];

        return WidgetFactory::makeTable($domId, $scenariosData, true, $hideColumns);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete($request)
    {
        $manager = $this->getScenariosManager();

        $domId = 'Suppression';
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect($this->baseAddress . 'scenarios');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "scenarios",
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
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        $domId = 'Edition';
        if (!$id = $request->getData('id')) {
            $domId = 'Ajout';
        }

        $this->page->addVar('title', "$domId du Scénario");

        $link = new Link(
            $domId,
            $this->baseAddress . "scenarios",
            "arrow_back",
            "white-text",
            "white-text"
        );
        $submitButton = new FlatButton(
            [
                'id' => 'submit',
                'title' => 'Valider',
                'color' => 'primaryTextColor',
                'type' => 'submit',
                'icon' => 'check',
                'wrapper' => 'col s12'
            ]
        );
        $cardTitle = $link->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $formBlock = $this->getBlock(__DIR__ . '/Block/scenariosForm.phtml', $id, $submitButton);
        $card->addContent($formBlock);
        $cards = [];
        $cards[] = $card;

        return $this->page->addVar('cards', $cards);
    }
}
