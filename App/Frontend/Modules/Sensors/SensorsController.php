<?php

namespace App\Frontend\Modules\Sensors;

use Entity\Sensor;
use FormBuilder\SensorsFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link\Link;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class SensorsController
 * @package App\Frontend\Modules\Sensors
 */
class SensorsController extends BackController
{
    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des sensors');
        $manager = $this->managers->getManagerOf('Sensors');
        $cards = [];
        $listeSensors = $manager->getList();
        $cards[] = $this->makeSensorsWidget($listeSensors);
        $addSensorsFab = new FloatingActionButton([
            'id' => "addSensorsFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "sensors-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addSensorsFab', $addSensorsFab);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Sensors');
        $domId = 'Suppression';
        if ($request->method() === 'POST' && $request->getExists('id')) {
            $id = $request->getData('id');
            $manager->delete($id);
            $this->app->httpResponse()->redirect($this->baseAddress . 'sensors');
            return;
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "sensors",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());
        $this->page->addVar('title', "Suppression du Sensor");
        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Sensors');
        $domId = 'Edition';

        if ($request->method() === 'POST') {
            $item = new Sensor([
                'radioid' => $request->postData('radioid'),
                'nom' => $request->postData('nom'),
                'categorie' => $request->postData('categorie'),
                'radioaddress' => $request->postData('radioaddress'),
                'releve' => "",
                'actif' => "",
                'valeur1' => "",
                'valeur2' => ""
            ]);

            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $prevItem = $manager->getUnique($id);

                $item->setId($id);
                $item->setReleve($prevItem->releve());
                $item->setActif($prevItem->actif());
                $item->setValeur1($prevItem->valeur1());
                $item->setValeur2($prevItem->valeur2());
            }
        } else {
            if ($request->getExists('id')) {
                $id = $request->getData("id");
                $item = $manager->getUnique($id);
            } else {
                $domId = 'Ajout';
                $item = new Sensor();
            }
        }

        $cards = [];

        $tmfb = new SensorsFormBuilder($item);
        $tmfb->build();
        $form = $tmfb->form();

        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect($this->baseAddress . 'sensors');
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "sensors",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));

        $cards[] = $card;

        $this->page->addVar('title', 'Edition du Sensor');
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param $sensors
     * @return \Materialize\Card\Card
     */
    public function makeSensorsWidget($sensors)
    {
        $domId = 'Sensors';
        $sensors = json_decode(json_encode($sensors), true);
        $sensorsData = [];

        foreach ($sensors as $sensor) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link(
                '',
                $this->baseAddress . "sensors-edit-" . $sensor["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "sensors-delete-" . $sensor["id"],
                'delete',
                'secondaryTextColor'
            );
            $sensor["editer"] = $linkEdit->getHtmlForTable();
            $sensor["supprimer"] = $linkDelete->getHtmlForTable();
            $sensorsData[] = $sensor;
        }
        $hiddenColumns = ['releve', 'actif', 'valeur1', 'valeur2'];
        $table = WidgetFactory::makeTable($domId, $sensorsData, true, $hiddenColumns);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($table->getHtml());

        return $card;
    }
}
