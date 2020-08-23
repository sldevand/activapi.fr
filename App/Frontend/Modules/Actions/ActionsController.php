<?php

namespace App\Frontend\Modules\Actions;

use App\Backend\Modules\Actions\ActionsController as ActionsBackController;
use App\Frontend\Modules\FormView;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use Materialize\Link\Link;
use Materialize\WidgetFactory;
use OCFram\HTTPRequest;

/**
 * Class ActionsController
 * @package App\Frontend\Modules\Actions
 */
class ActionsController extends ActionsBackController
{
    use FormView;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $actions = parent::executeGet($request);

        $this->page->addVar('title', 'Gestion des actions');

        $cards = [];
        $cards[] = $this->makeActionsWidget($actions);
        $addActionFab = new FloatingActionButton([
            'id' => "addActionFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "actions-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addActionFab', $addActionFab);
    }

    /**
     * @param array $actions
     * @return \Materialize\Card\Card
     */
    public function makeActionsWidget($actions)
    {
        $domId = 'Actions';
        $actions = json_decode(json_encode($actions), true);
        $card = WidgetFactory::makeCard($domId, $domId);
        if (!$actions) {
            $card->addContent("Pas d'actions");
            return $card;
        }
        $table = $this->createActionsTable($actions, 'actions-table');
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param array $actions
     * @param string $domId
     * @return \Materialize\Table
     */
    public function createActionsTable($actions, $domId)
    {
        $actionsData = [];
        foreach ($actions as $action) {
            $linkEdit = new Link(
                '',
                $this->baseAddress . "actions-edit-" . $action["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "actions-delete-" . $action["id"],
                'delete',
                'secondaryTextColor'
            );
            $action["editer"] = $linkEdit->getHtmlForTable();
            $action["supprimer"] = $linkDelete->getHtmlForTable();
            $actionsData[] = $action;
        }

        $hideColumns = ['data','actionneurId','actionneur','etat'];

        return WidgetFactory::makeTable($domId, $actionsData, true, $hideColumns);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete($request)
    {
        $manager = $this->getActionManager();

        $domId = 'Suppression';
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect($this->baseAddress . 'actions');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "actions",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression de l'Action");
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

        $this->page->addVar('title', "$domId de l'Action");

        $link = new Link(
            $domId,
            $this->baseAddress . "actions",
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
        $formBlock = $this->getBlock(__DIR__ . '/Block/actionsForm.phtml', $id, $submitButton);
        $card->addContent($formBlock);
        $cards = [];
        $cards[] = $card;

        return $this->page->addVar('cards', $cards);
    }
}
