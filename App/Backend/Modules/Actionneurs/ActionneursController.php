<?php

namespace App\Backend\Modules\Actionneurs;

use Entity\Actionneur;
use Exception;
use Model\ActionneursManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use Psinetron\SocketIO;

/**
 * Class ActionneursController
 * @package App\Backend\Modules\Actionneurs
 */
class ActionneursController extends BackController
{
    /** @var ActionneursManagerPDO $manager */
    protected $manager;

    /**
     * ActionneursController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Actionneurs');
    }

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
     * @throws Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $categorie = $request->getData('categorie');
        $this->page->addVar('actionneurs', $this->manager->getList($categorie));
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeInsert(HTTPRequest $request)
    {
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

        try {
            $this->manager->save($actionneur);
            return $this->page->addVar('output', ['message' => 'Ok']);
        } catch (Exception $e) {
            return $this->page->addVar('output', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeUpdate(HTTPRequest $request)
    {
        try {
            $jsonPost = $request->getJsonPost();
            $actionneur = new Actionneur(
                [
                    'id' => $jsonPost['id'],
                    'nom' => $jsonPost['nom'],
                    'module' => $jsonPost['module'],
                    'protocole' => $jsonPost['protocole'],
                    'adresse' => $jsonPost['adresse'],
                    'type' => $jsonPost['type'],
                    'radioid' => $jsonPost['radioid'],
                    'etat' => $jsonPost['etat'],
                    'categorie' => $jsonPost['categorie'],
                ]
            );
        } catch (Exception $e) {
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
        }

        try {
            $this->manager->save($actionneur);
            return $this->page->addVar('output', ['message' => 'Ok']);
        } catch (Exception $e) {
            return $this->page->addVar('output', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeCommand(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $etat = $request->getData('etat');

        if (empty($id) || !isset($etat)) {
            return $this->page->addVar('output', ['error' => 'No id or etat given']);
        }

        /** @var Actionneur $actionneur */
        $actionneur = $this->manager->getUnique($id);
        if (empty($actionneur)) {
            return $this->page->addVar('output', ['error' => 'No actionneur on id ' . $id]);
        }

        $actionneur->setEtat($etat);
        $action = 'update' . ucfirst($actionneur->getCategorie());
        if ($actionneur->getCategorie() == "dimmer") {
            $action .= "Persist";
        }
        $dataJSON = json_encode($actionneur);

        $ip = $this->app()->config()->getEnv('NODE_IP');
        $port = $this->app()->config()->getEnv('NODE_PORT');

        /** @var SocketIO $socketio */
        $socketio = new SocketIO();
        if (!$socketio->send($ip, $port, $action, $dataJSON)) {
            return $this->page->addVar('output', ['error' => 'Node error']);
        }

        return $this->page->addVar('output', ['message' => 'Ok']);
    }
}
