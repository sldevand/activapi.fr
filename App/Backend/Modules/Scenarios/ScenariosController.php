<?php

namespace App\Backend\Modules\Scenarios;

use Entity\Actionneur;
use Entity\Scenario;
use Model\ActionneursManagerPDO;
use Model\ScenariosManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ScenariosController
 * @package App\Backend\Modules\Scenarios
 */
class ScenariosController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $id = $request->postData('id');
        $this->managers->getManagerOf('Scenarios')->delete($id);
        $this->page->addVar('result', 'Scenario ' . $id . 'Supprimé');
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeDeleteitem(HTTPRequest $request)
    {
        $id = $request->postData('id');
        $this->managers->getManagerOf('Scenarios')->deleteItem($id);
        $this->page->addVar('result', 'Item ' . $id . 'Supprimé');
    }

    /**
     * @param HTTPRequest $request
     * @return array
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $id = $request->getData("id");
        $scenarioManager = $this->managers->getManagerOf('Scenarios');
        /** @var ActionneursManagerPDO $actionneursManager */
        $actionneursManager = $this->managers->getManagerOf('Actionneurs');
        if (empty($id)) {
            $listeScenarios = $scenarioManager->getList();
        } else {
            $listeScenarios = $scenarioManager->getScenario($id);
        }
        $scenariosTab = [];
        /**
         * @var string $key
         * @var Scenario $scenario
         */
        foreach ($listeScenarios as $key => $scenario) {
            /** @var Actionneur $actionneur */
            $actionneur = $actionneursManager->getUnique($scenario->actionneurid());
            $listeScenarios[$key]->setActionneur($actionneur);
            $actionneur->setEtat($listeScenarios[$key]->etat());
            $scenariosTab[$scenario->scenarioid()]["nom"] = $listeScenarios[$key]->nom();
            $scenariosTab[$scenario->scenarioid()]["scenarioid"] = $scenario->scenarioid();
            $tempActionneur = $listeScenarios[$key]->actionneur();
            $scenariosTab[$scenario->scenarioid()]["data"][$scenario->id()] = $tempActionneur;
        }
        $this->page->addVar('scenarios', $scenariosTab);

        return $scenariosTab;
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeInsert(HTTPRequest $request)
    {
        /** @var ScenariosManagerPDO $manager */
        $manager = $this->managers->getManagerOf('Scenarios');

        if (!$request->postsExist()) {
            $request->getJsonPost();
        }

        $nom = $request->postData('nom');
        $actionneurId = $request->postData('actionneurid');
        $etat = $request->postData('etat');

        $scenario = new Scenario(
            [
                'nom' => $nom,
                'actionneurid' => $actionneurId,
                'etat' => $etat

            ]
        );
        $add = $manager->add($scenario);
        $this->page->addVar('result', $add);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeUpdate(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Scenarios');
        $scenario = new Scenario(
            [
                'id' => $request->postData('id'),
                'nom' => $request->postData('nom'),
                'scenarioid' => '',
                'actionneurid' => '',
                'etat' => ''
            ]
        );
        $this->page->addVar('result', $manager->update($scenario));
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeUpdateitem(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Scenarios');
        $scenario = new Scenario(
            [
                'id' => $request->postData('id'),
                'nom' => $request->postData('nom'),
                'scenarioid' => $request->postData('scenarioid'),
                'actionneurid' => $request->postData('actionneurid'),
                'etat' => $request->postData('etat')
            ]
        );
        $this->page->addVar('result', $manager->updateItem($scenario));
    }
}
