<?php

namespace App\Backend\Modules\Actionneurs;

use Entity\Actionneur;
use Exception;
use Model\ActionneursManagerPDO;
use Model\Scenario\ActionManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;
use Psinetron\SocketIO;


/**
 * Class ActionneursController
 * @package App\Backend\Modules\Actionneurs
 */
class ActionneursController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $this->managers->getManagerOf('Actionneurs')->delete($id);
        $this->app->user()->setFlash('L\'actionneur a bien été supprimé !');
        $this->app->httpResponse()->redirect('.');
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $categorie = $request->getData('categorie');

        $manager = $this->managers->getManagerOf('Actionneurs');
        $this->page->addVar('actionneurs', $manager->getList($categorie));
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeInsert(HTTPRequest $request)
    {
        /** @var ActionManagerPDO $manager */
        $manager = $this->managers->getManagerOf('Actionneurs');

        $actionneur = new Actionneur(
            [
                'nom' => $request->postData('nom'),
                'module' => $request->postData('module'),
                'protocole' => $request->postData('protocole'),
                'adresse' => $request->postData('adresse'),
                'type' => $request->postData('type'),
                'radioid' => $request->postData('radioid'),
                'etat' => $request->postData('etat'),
                'categorie' => $request->postData('categorie'),
            ]
        );

        $manager->save($actionneur);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeUpdate(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Actionneurs');

        $actionneur = new Actionneur(
            [
                'id' => $request->postData('id'),
                'nom' => $request->postData('nom'),
                'module' => $request->postData('module'),
                'protocole' => $request->postData('protocole'),
                'adresse' => $request->postData('adresse'),
                'type' => $request->postData('type'),
                'radioid' => $request->postData('radioid'),
                'etat' => $request->postData('etat'),
                'categorie' => $request->postData('categorie'),
            ]
        );

        $manager->save($actionneur);
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeCommand(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $etat = $request->getData('etat');

        if (empty($id) || empty($etat)) {
            return $this->page->addVar('output', ['error' => 'No id or etat given']);
        }

        /** @var ActionneursManagerPDO $manager */
        $manager = $this->managers->getManagerOf('Actionneurs');
        /** @var Actionneur $actionneur */
        $actionneur = $manager->getUnique($id);
        if (empty($actionneur)) {
            return $this->page->addVar('output', ['error' => 'No actionneur on id ' . $id]);
        }

        $actionneur->setEtat($etat);
        $action = 'update' . ucfirst($actionneur->getCategorie());
        if ($actionneur->getCategorie() == "dimmer") {
            $action .= "Persist";
        }
        $dataJSON = json_encode($actionneur);

        $ip = $this->app()->config()->get('nodeIP');
        $port = $this->app()->config()->get('nodePort');

        /** @var SocketIO $socketio */
        $socketio = new SocketIO();
        if (!$socketio->send($ip, $port, $action, $dataJSON)) {
            return $this->page->addVar('output', ['error' => 'Node error']);
        }

        return $this->page->addVar('output', ['message' => 'Ok']);
    }
}
