<?php

namespace App\Frontend\Modules\Scenarios;

use App\Backend\Modules\Scenarios\ScenariosController as ScenariosBackController;
use App\Frontend\Modules\FormView;
use Entity\Actionneur;
use Entity\Scenario;
use FormBuilder\ScenariosFormBuilder;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use Model\ScenariosManagerPDO;
use OCFram\FormHandler;
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
     * @param array $scenarios
     * @return \Materialize\Card\Card
     */
    public function makeScenariosWidget($scenarios)
    {
        $domId = 'Scenarios';

        $scenarios = json_decode(json_encode($scenarios), true);
        $scenariosData = [];

        foreach ($scenarios as $scenario) {
            $linkEdit = new Link(
                '',
                "../activapi.fr/scenarios-edit-" . $scenario["scenarioid"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                "../activapi.fr/scenarios-delete-" . $scenario["scenarioid"],
                'delete',
                'secondaryTextColor'
            );
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
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        /** @var ScenariosManagerPDO $manager */
        $manager = $this->managers->getManagerOf('Scenarios');
        $actionneursManager = $this->managers->getManagerOf('Actionneurs');
        $actionneursList = $actionneursManager->getList();
        $domId = 'Edition';

        if ($request->method() == 'POST') {
            $item = new Scenario(
                [
                    'nom' => $request->postData('nom'),
                    'scenarioid' => $request->postData('scenarioid')
                ]
            );

            $actionneurs = $request->postData('actionneurs');

            if ($request->getExists('scenarioid')) {
                $id = $request->getData('scenarioid');
                $item->setId($id);
                $item->sequence = $manager->getSequence($id);
            }
            foreach ($actionneurs as $key => $actionneur) {
                foreach ($actionneur as $num => $elt) {
                    $radioId = $elt;
                    $etat = $actionneurs['etat'][$num];
                    $actionneurid = $actionneurs['actionneurid'][$num];
                    $item->addActionneur(new Actionneur(
                        [
                            'id' => $actionneurid,
                            'etat' => $etat,
                            'radioid' => $radioId
                        ]
                    ));
                }
                break;
            }
        } else {
            if ($request->getExists('scenarioid')) {
                $id = $request->getData("scenarioid");
                $item = $manager->getScenario($id);
                $item->sequence = $manager->getSequence($id);
            } else {
                $domId = 'Ajout';
                $item = new Scenario();
                $item->sequence = $manager->getSequence();
            }
        }

        $item->actionneursList = $actionneursList;

        $cards = [];
        $tmfb = new ScenariosFormBuilder($item);
        $form = $tmfb->form();

        $fh = new FormHandler($form, $manager, $request);
        if ($fh->process()) {
            $this->app->httpResponse()->redirect('../activapi.fr/scenarios');
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

        $formBlock = $this->getBlock(__DIR__ . '/Block/scenariosForm.phtml', $form, $submitButton);
        $card->addContent($formBlock);
        $cards[] = $card;

        $this->page->addVar('title', "Edition du Scénario");
        $this->page->addVar('cards', $cards);
    }
}
