<?php

namespace App\Frontend\Modules\Thermostat;

use Entity\ThermostatMode;
use Entity\ThermostatPlanif;
use FormBuilder\ThermostatModesFormBuilder;
use FormBuilder\ThermostatPlanifFormBuilder;
use FormBuilder\ThermostatPlanifNameFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class ThermostatController
 * @package App\Frontend\Modules\Thermostat
 */
class ThermostatController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion du Thermostat');

        $managerThermostats = $this->managers->getManagerOf('Thermostat');
        $managerThermostatPlanif = $this->managers->getManagerOf('ThermostatPlanif');
        $managerActionneurs = $this->managers->getManagerOf('Actionneurs');
        $managerSensors = $this->managers->getManagerOf('Mesures');

        $thermostats = $managerThermostats->getList();
        $planifs = $managerThermostatPlanif->getList(1);

        $sensors = $managerSensors->getSensors("thermostat");
        $sensorsTemoin = $managerSensors->getSensors("thermo");
        $actionneurs = $managerActionneurs->getList("thermostat");

        $sensorTemoin = null;

        foreach ($sensorsTemoin as $key => $sensor) {
            if ($sensor['id'] == $thermostats[0]['sensorid']) $sensorTemoin = $sensor;
        }
        $cards = [];


        foreach ($thermostats as $thermostat) {

            $name = "Aucun";
            if ($thermostat->planning() > 0) {
                $name = $managerThermostatPlanif->getNom($thermostat->planning())->nom();
            }

            $thermostat->setPlanningName($name);
        }

        $cards[] = $this->makeThermostatWidget($thermostats, $planifs);
        $cards[] = $this->makeSensorsWidget($sensors, $sensorTemoin);
        $cards[] = $this->makeActionneurWidget($actionneurs);

        $this->page->addVar('cards', $cards);

    }

    /**
     * @param $thermostats
     * @param $planifs
     * @return \Materialize\Card
     */
    public function makeThermostatWidget($thermostats, $planifs)
    {
        $thermostats = json_decode(json_encode($thermostats), TRUE);

        $domId = 'Thermostat';
        $tableThermostatDatas = [];
        $hideColumns = ['id', 'nom', 'modeid', 'planning', 'manuel', 'sensor', 'sensorid'];


        foreach ($thermostats as $key => $thermostat) {
            $thermostat["mode"] = $thermostat["mode"]["nom"];
            $thermostat["etat"] = $this->iconifyPowerResult($thermostat["etat"]);


            $tableThermostatDatas[] = $thermostat;
        }

        $tableThermostat = WidgetFactory::makeTable($domId, $tableThermostatDatas, false, $hideColumns);
        $cardContent = $tableThermostat->getHtml();
        return WidgetFactory::makeCard($domId, 'Thermostat', $cardContent);
    }

    /**
     * @param $state
     * @return string
     */
    public function iconifyPowerResult($state)
    {
        if ($state == 1) {
            $color = "secondaryTextColor";
        } else {
            $color = "grey-text";
        }
        $html = '<i class="mdi mdi-power ' . $color . ' "></i>';

        return $html;

    }

    /**
     * @param $sensors
     * @param $sensorTemoin
     * @return \Materialize\Card
     */
    public function makeSensorsWidget($sensors, $sensorTemoin)
    {
        $datasSensors = json_decode(json_encode($sensors), TRUE);
        $datasSensorsTemoin = json_decode(json_encode($sensorTemoin), TRUE);
        $domId = 'Capteurs';

        $tableDatas = [];
        $tableDatasSensorTemoin = [];

        foreach ($datasSensors as $key => $data) {
            $data["actif"] = $this->iconifyResult($data["actif"]);
            $tableDatas[] = $data;
        }
        $datasSensorsTemoin["actif"] = $this->iconifyResult($datasSensorsTemoin["actif"]);
        $tableDatasSensorTemoin[] = $datasSensorsTemoin;


        $tableSensor = WidgetFactory::makeTable($domId, $tableDatas, false);
        $tableSensorTemoin = WidgetFactory::makeTable($domId, $tableDatasSensorTemoin, false);


        $cardContent = '<div class="flow-text">Capteur Interne</div>';
        $cardContent .= $tableSensor->getHtml();

        $cardContent .= '<div class="flow-text">Capteur Témoin</div>';
        $cardContent .= $tableSensorTemoin->getHtml();
        return WidgetFactory::makeCard($domId, $domId, $cardContent);
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

    /**
     * @param $actionneur
     * @return \Materialize\Card
     */
    public function makeActionneurWidget($actionneur)
    {
        $domId = 'Actionneur';
        $tableThermostat = WidgetFactory::makeTable($domId, $actionneur);
        $cardContent = $tableThermostat->getHtml();

        return WidgetFactory::makeCard($domId, $domId, $cardContent);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeLog(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Thermostat Log');
        $domId = 'Log';
        $nDerniersLogs = 10;

        if ($request->getData("nbLogs")) {
            if ($request->getData("nbLogs") > 100) {
                $nDerniersLogs = 100;
            } else {
                $nDerniersLogs = $request->getData("nbLogs");
            }
        }

        $cards = [];
        $manager = $this->managers->getManagerOf('Thermostat');

        $logList = $manager->getLogList(0, $nDerniersLogs);
        $logListTab = json_decode(json_encode($logList), TRUE);
        $tableDatas = [];

        foreach ($logListTab as $key => $log) {
            $log["etat"] = $this->iconifyPowerResult($log["etat"]);
            $tableDatas[] = $log;
        }

        $nombreLogs = $manager->countLogs();

        $table = WidgetFactory::makeTable($domId, $tableDatas);
        $cardContent = '<hr><span>Nombre de logs : ' . $nombreLogs . '</br>';
        $cardContent .= 'Voici la liste des ' . $nDerniersLogs . ' derniers Logs</span><hr>';
        $cardContent .= $table->getHtml();
        $card = WidgetFactory::makeCard($domId, $domId, $cardContent);

        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('nombreLogs', $nombreLogs);
        $this->page->addVar('nDerniersLogs', $nDerniersLogs);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeModes(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des Modes');
        $manager = $this->managers->getManagerOf('ThermostatModes');

        $modes = json_decode(json_encode($manager->getList()), TRUE);
        $modesData = [];
        $domId = "Modes";
        $hideColumns = ['id'];

        foreach ($modes as $mode) {
            //DATA PREPARE FOR TABLE
            $linkEdit = new Link('', "../activapi.fr/thermostat-modes-edit-" . $mode["id"], 'edit', 'primaryTextColor');
            $linkDelete = new Link('', "../activapi.fr/thermostat-modes-delete-" . $mode["id"], 'delete', 'secondaryTextColor');
            $mode["editer"] = $linkEdit->getHtmlForTable();
            $mode["supprimer"] = $linkDelete->getHtmlForTable();
            $modesData[] = $mode;
        }

        $table = WidgetFactory::makeTable($domId, $modesData, false, $hideColumns);
        $cardTitle = $domId;
        $cardContent = $table->getHtml();
        $addModeFab = new FloatingActionButton([
            'id' => "addModeFab",
            'fixed' => true,
            'icon' => "add",
            'href' => "../activapi.fr/thermostat-modes-add"
        ]);

        $cardContent .= $addModeFab->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);
        $cards[] = $card;

        $this->page->addVar('cards', $cards);

    }

    /**
     * @param HTTPRequest $request
     */
    public function executeModesDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');

        $domId = 'Suppression';
        if ($request->method() == 'POST') {

            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect('../activapi.fr/thermostat-modes');
            }
        }

        $cardContent = '<p class="flow-text">Voulez-vous vraiment supprimer ce mode?</p>';
        $cardContent .= '<form action="" method="post">';
        $cardContent .= '<input class="btn-flat" type="submit" value="Supprimer" />';
        $cardContent .= '</form>';

        $link = new Link($domId,
            "../activapi.fr/thermostat-modes",
            "arrow_back",
            "white-text",
            "white-text");

        $cardTitle = $link->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);

        $this->page->addVar('title', 'Suppression du Mode');
        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeModesEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');
        $modes = $manager->getList();
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

        $cardContent = '<form action="" method="post">';
        $cardContent .= $form->createView();
        $cardContent .= '<input class="btn-flat" type="submit" value="Valider" />';
        $cardContent .= '</form>';
        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect('../activapi.fr/thermostat-modes');
        }

        $link = new Link($domId,
            "../activapi.fr/thermostat-modes",
            "arrow_back",
            "white-text",
            "white-text");

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);
        $cards[] = $card;

        $this->page->addVar('title', 'Edition du Mode');
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executePlanif(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion du Planning');

        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $thermostatPlanningsContainer = $manager->getListArray();

        $cards = [];

        foreach ($thermostatPlanningsContainer as $thermostatPlannings) {

            $thermostatDatas = [];
            foreach ($thermostatPlannings as $thermostatPlanningObj) {
                $thermostatPlanning = json_decode(json_encode($thermostatPlanningObj), true);

                //DATA PREPARE FOR TABLE
                $hideColumns = ['id', 'nomid', 'nom', 'modeid', 'defaultModeid'];
                $thermostatPlanning["jour"] = DateFactory::toStrDay($thermostatPlanning['jour']);
                $thermostatPlanning["mode"] = $thermostatPlanning["mode"]["nom"];
                $thermostatPlanning["defaultMode"] = $thermostatPlanning["defaultMode"]["nom"];
                $linkEdit = new Link('', "../activapi.fr/thermostat-planif-edit-" . $thermostatPlanning["id"], 'edit', 'primaryTextColor');
                $thermostatPlanning["editer"] = $linkEdit->getHtmlForTable();
                $domId = $thermostatPlanning["nom"];
                $thermostatDatas[] = $thermostatPlanning;
            }

            $table = WidgetFactory::makeTable($domId, $thermostatDatas, true, $hideColumns);

            $cardTitle = 'Thermostat : Planning  ' . $domId;
            $cardContent = $table->getHtml();

            $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);
            $cards[] = $card;
        }

        $addPlanifFab = new FloatingActionButton([
            'id' => "addPlanifFab",
            'fixed' => true,
            'icon' => "add",
            'href' => "../activapi.fr/thermostat-planif-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addPlanifFab', $addPlanifFab);
    }

    /**
     * @param HTTPRequest $request
     */
    function executePlanifAdd(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $domId = 'Ajout';
        $cardTitle = 'Thermostat : Planning  ' . $domId;
        $cardContent = '';
        $name = null;

        if ($request->method() == 'POST') {
            if ($request->postExists('nom')) {
                $name = $request->postData('nom');
            }

            if (!is_null($name)) {
                $result = $manager->addPlanifTable($name);
                if ($result > 0) {
                    $cardContent = '<p class="flow-text">OK</p>';
                } else {
                    $cardContent = "Ce nom existe déjà!";
                }
            } else {
                $cardContent = "Le nom est vide";
            }
        }

        $item = new ThermostatPlanif(['nom' => $name]);
        $fb = new ThermostatPlanifNameFormBuilder($item);
        $fb->build();
        $form = $fb->form();

        $cardContent = '<form action="" method="post">';
        $cardContent .= $form->createView();
        $cardContent .= '<input class="btn-flat" type="submit" value="Valider" />';
        $cardContent .= '</form>';
        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect('../activapi.fr/thermostat-planif');
        }

        $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);

        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executePlanifEdit(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $modes = $manager->getModes();

        if ($request->method() == 'POST') {

            $item = new ThermostatPlanif([
                'jour' => $request->postData('jour'),
                'modeid' => $request->postData('modeid'),
                'defaultModeid' => $request->postData('defaultModeid'),
                'heure1Start' => $request->postData('heure1Start'),
                'heure1Stop' => $request->postData('heure1Stop'),
                'heure2Start' => $request->postData('heure2Start'),
                'heure2Stop' => $request->postData('heure2Stop'),
                'nomid' => $request->postData('nomid')
            ]);

            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $item->setId($id);
            }

        } else {
            if ($request->getExists('id')) {

                $id = $request->getData("id");
                $item = $manager->getUnique($id);
            }
        }
        $cards = [];


        $domId = 'Edition';
        $item->modes = $modes;

        $tpfb = new ThermostatPlanifFormBuilder($item);
        $tpfb->build();
        $form = $tpfb->form();

        $cardContent = '<form action="" method="post">';
        $cardContent .= $form->createView();
        $cardContent .= '<input class="btn-flat" type="submit" value="Valider" />';
        $cardContent .= '</form>';
        $fh = new FormHandler($form, $manager, $request);

        if ($fh->process()) {
            $this->app->httpResponse()->redirect('../activapi.fr/thermostat-planif');
        }

        $link = new Link("Edition",
            "../activapi.fr/thermostat-planif",
            "arrow_back",
            "white-text",
            "white-text");

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle, $cardContent);
        $cards[] = $card;


        $this->page->addVar('title', 'Edition du Planning');
        $this->page->addVar('cards', $cards);
    }
}
