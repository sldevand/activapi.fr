<?php

namespace App\Backend\Modules\Actionneurs;

use Entity\Actionneur;
use Model\Scenario\ActionManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;


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
     * @throws \Exception
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
}
