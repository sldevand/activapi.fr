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
        /** @var ScenariosManagerPDO $scenarioManager */
        $scenarioManager = $this->managers->getManagerOf('Scenarios');
        /** @var ActionneursManagerPDO $actionneursManager */
        $actionneursManager = $this->managers->getManagerOf('Actionneurs');
        if (empty($id)) {
            $sequences = $scenarioManager->getList();
        } else {
            $sequences = [$scenarioManager->getScenario($id)];
        }
        $scenariosTab = [];
        /** @var Scenario $sequence */
        foreach ($sequences as $sequence) {
            /** @var Actionneur $actionneur */
            $actionneurs = $actionneursManager->getList();
            $sequence->setActionneurs($actionneurs);
            $scenariosTab[$sequence->scenarioid()]["nom"] = $sequence->nom();
            $scenariosTab[$sequence->scenarioid()]["scenarioid"] = $sequence->scenarioid();

            foreach ($sequence->getActionneurs() as $tempActionneur) {
                $scenariosTab[$sequence->scenarioid()]["data"][$sequence->id()] = $tempActionneur;
            }

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
