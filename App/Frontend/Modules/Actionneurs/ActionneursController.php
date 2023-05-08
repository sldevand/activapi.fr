<?php

namespace App\Frontend\Modules\Actionneurs;

use Entity\Actionneur;
use FormBuilder\ActionneursFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\FormView;
use Materialize\Link\Link;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class ActionneursController
 * @package App\Frontend\Modules\Actionneurs
 */
class ActionneursController extends BackController
{
    use FormView;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des actionneurs');

        $manager = $this->managers->getManagerOf('Actionneurs');
        $cards = [];

        $listeActionneurs = $manager->getList();
        $cards[] = $this->makeActionneursWidget($listeActionneurs);
        $addActionneurFab = new FloatingActionButton([
            'id' => "addActionneurFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "actionneurs-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addActionneurFab', $addActionneurFab);
    }

    /**
     * @param $actionneurs
     * @return \Materialize\Card\Card
     */
    public function makeActionneursWidget($actionneurs)
    {
        $domId = 'Actionneurs';

        $actionneurs = json_decode(json_encode($actionneurs), true);
        $actionneursData = [];

        foreach ($actionneurs as $actionneur) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link(
                '',
                $this->baseAddress . "actionneurs-edit-" . $actionneur["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "actionneurs-delete-" . $actionneur["id"],
                'delete',
                'secondaryTextColor'
            );
            $actionneur["editer"] = $linkEdit->getHtmlForTable();
            $actionneur["supprimer"] = $linkDelete->getHtmlForTable();
            $actionneursData[] = $actionneur;
        }
        $hiddenColumns = ['protocole', 'type', 'etat', 'categorie'];
        $table = WidgetFactory::makeTable($domId, $actionneursData, true, $hiddenColumns);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Actionneurs');

        $domId = 'Suppression';
        if ($request->method() === 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->deleteActionCache('index');
                $this->deleteActionCache('index', 'Frontend', 'Actions');
                $this->app->httpResponse()->redirect($this->baseAddress . 'actionneurs');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . 'actionneurs',
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression de l'Actionneur");
        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Actionneurs');
        $actionneurs = $manager->getList();
        $domId = 'Edition';

        if ($request->method() === 'POST') {
            $item = new Actionneur([
                'nom' => $request->postData('nom'),
                'module' => $request->postData('module'),
                'protocole' => $request->postData('protocole'),
                'adresse' => $request->postData('adresse'),
                'type' => $request->postData('type'),
                'radioid' => $request->postData('radioid'),
                'etat' => $request->postData('etat'),
                'categorie' => $request->postData('categorie')
            ]);

            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $item->setId($id);
            }
        } else {
            if ($request->getExists('id')) {
                $id = $request->getData("id");
                $item = $manager->getUnique($id);
            } else {
                $domId = 'Ajout';
                $item = new Actionneur();
            }
        }

        $cards = [];

        $tmfb = new ActionneursFormBuilder($item);
        $tmfb->build();
        $form = $tmfb->form();

        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->deleteActionCache('index');
            $this->app->httpResponse()->redirect($this->baseAddress . 'actionneurs');
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "actionneurs",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));
        $cards[] = $card;

        $this->page->addVar('title', "Edition de l'actionneur");
        $this->page->addVar('cards', $cards);
    }
}
