<?php

namespace App\Frontend\Modules\ThermostatModes;

use Entity\ThermostatMode;
use FormBuilder\ThermostatModesFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\Spinner\Spinner;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class ThermostatModesController
 * @package App\Frontend\Modules\ThermostatModes
 */
class ThermostatModesController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des Modes');
        $manager = $this->managers->getManagerOf('ThermostatModes');

        $modes = json_decode(json_encode($manager->getAll()), true);
        $modesData = [];
        $domId = "Modes";
        $hideColumns = ['id'];

        foreach ($modes as $mode) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link('', $this->baseAddress . "thermostat-modes-edit-" . $mode["id"], 'edit', 'primaryTextColor');
            $linkDelete = new Link('', $this->baseAddress . "thermostat-modes-delete-" . $mode["id"], 'delete', 'secondaryTextColor');
            $mode["editer"] = $linkEdit->getHtmlForTable();
            $mode["supprimer"] = $linkDelete->getHtmlForTable();
            $modesData[] = $mode;
        }

        $table = WidgetFactory::makeTable($domId, $modesData, false, $hideColumns);

        $cardTitle = $domId;
        $cardContent = $table->getHtml();

        if (count($modes) < 4) {
            $addModeFab = new FloatingActionButton([
                'id' => "addModeFab",
                'fixed' => true,
                'icon' => "add",
                'href' => $this->baseAddress . "thermostat-modes-add"
            ]);
            $cardContent .= $addModeFab->getHtml();
        }

        $cardModes = WidgetFactory::makeCard($domId, $cardTitle);
        $cardModes->addContent($cardContent);

        $cardTitle = $this->syncCardTitleView();

        $cardSyncModes = WidgetFactory::makeCard('sync-mode-card', $cardTitle);

        /** @var \Materialize\Spinner\Spinner $spinner */
        $spinner = new Spinner(['id' => 'spinner']);
        $cardSyncModes->addContent($spinner->getHtml());

        $cards[] = $cardModes;
        $cards[] = $cardSyncModes;
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');

        $domId = 'Suppression';
        if ($request->method() === 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect($this->baseAddress . 'thermostat-modes');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "thermostat-modes",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', 'Suppression du Mode');
        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');
        $domId = 'Edition';
        if ($request->method() == 'POST') {
            $item = new ThermostatMode([
                'nom' => $request->postData('nom'),
                'consigne' => $request->postData('consigne'),
                'delta' => $request->postData('delta')
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
                $item = new ThermostatMode();
            }
        }
        $cards = [];

        $tmfb = new ThermostatModesFormBuilder($item);
        $tmfb->build();
        $form = $tmfb->form();

        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect($this->baseAddress . 'thermostat-modes');
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "thermostat-modes",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));
        $cards[] = $card;

        $this->page->addVar('title', 'Edition du Mode');
        $this->page->addVar('cards', $cards);
    }

    /**
     * @return false|string
     */
    public function syncCardTitleView()
    {
        return $this->getBlock(MODULES . '/ThermostatModes/Block/syncCardTitleView.phtml');
    }
}
