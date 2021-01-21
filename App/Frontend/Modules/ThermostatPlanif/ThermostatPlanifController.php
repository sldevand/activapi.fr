<?php

namespace App\Frontend\Modules\ThermostatPlanif;

use App\Frontend\Modules\ThermostatPlanif\Block\PlanifCardList;
use App\Frontend\Modules\ThermostatPlanif\Form\ThermostatPlanifFormHandler;
use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;
use FormBuilder\ThermostatPlanifFormBuilder;
use FormBuilder\ThermostatPlanifNameFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\FormView;
use Materialize\WidgetFactory;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ThermostatPlanifController
 * @package App\Frontend\Modules\ThermostatPlanif
 */
class ThermostatPlanifController extends BackController
{
    use FormView;

    /** @var \Model\ThermostatPlanifManagerPDO */
    protected $manager;

    /**
     * ThermostatPlanifController constructor.
     * @param \OCFram\Application $app
     * @param $module
     * @param $action
     * @throws \Exception
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('ThermostatPlanif');
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $thermostatPlanningsContainer = $this->manager->getListArray();
        $hideColumns = ['id', 'nomid', 'nom', 'modeid', 'defaultModeid'];

        $planifCardList = new PlanifCardList($this->baseAddress);
        $cards = $planifCardList->create($thermostatPlanningsContainer, $hideColumns);

        $addPlanifFab = new FloatingActionButton(
            [
                'id' => "addPlanifFab",
                'fixed' => true,
                'icon' => "add",
                'href' => $this->baseAddress . "thermostat-planif-add"
            ]
        );

        $this->page
            ->addVar('title', 'Gestion du Planning')
            ->addVar('cards', $cards)
            ->addVar('addPlanifFab', $addPlanifFab);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeAdd(HTTPRequest $request)
    {
        $isPost = $request->method() === HTTPRequest::POST;
        $name = $isPost ? $request->postData('nom') : '';

        $thermostatPlanifNom = new ThermostatPlanifNom(['nom' => $name]);
        $item = new ThermostatPlanif(['nom' => $thermostatPlanifNom]);
        $fb = new ThermostatPlanifNameFormBuilder($item);
        $form = $fb->build();
        $fh = new ThermostatPlanifFormHandler($form, $this->manager, $request);
        if ($fh->process()) {
            $this->redirectBack($thermostatPlanifNom->nom());
        }

        if ($messages = $fh->getMessageHandler()->getMessages()) {
            $this->app->user()->setFlash($messages);
        }

        $domId = 'Ajout';
        $cardTitle = 'Thermostat : Planning  ' . $domId;
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));

        $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        if ($request->method() === HTTPRequest::POST) {
            $item = $this->createThermostatPlanifFromPost($request);
        } else {
            if ($request->getExists('id')) {
                $id = $request->getData("id");
                $item = $this->manager->getUnique($id);
            }
        }

        /** @var \Model\ThermostatModesManagerPDO $modesManager */
        $modesManager = $this->managers->getManagerOf('ThermostatModes');
        $modes = $modesManager->getList();

        $tpfb = new ThermostatPlanifFormBuilder($item);
        $tpfb->addData('modes', $modes);
        $form = $tpfb->build();

        $fh = new ThermostatPlanifFormHandler($form, $this->manager, $request);
        if ($fh->process()) {
            $this->redirectBack($item->nom()->nom());
        }

        $domId = 'Edition';
        $backUrl = $this->baseAddress . 'thermostat-planif';
        $cardTitle = WidgetFactory::makeBackArrow($domId, $backUrl . '#' . $item->getNom())->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->editFormView($form));

        $this->page
            ->addVar('title', 'Edition du Planning')
            ->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete(HTTPRequest $request)
    {
        $nom = '';
        if ($request->method() === HTTPRequest::POST) {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $result = $this->manager->delete($id);
                if ($result) {
                    $this->app()->user()->setFlash('Planning supprimé');
                }
                $this->redirectBack();
            }
        } else {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $nom = $this->manager->getNom($id);
            }
        }

        $domId = 'Suppression';
        $backUrl = $this->baseAddress . 'thermostat-planif';
        $cardTitle = WidgetFactory::makeBackArrow($domId, $backUrl . '#' . $nom)->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page
            ->addVar('title', "Suppression du Planning $nom")
            ->addVar('card', $card);
    }

    /**
     * @param string $anchor
     */
    protected function redirectBack(string $anchor = '')
    {
        $this->app->httpResponse()->redirect($this->getRouteUrl($anchor));
    }


    /**
     * @param string $anchor
     * @return string
     */
    protected function getRouteUrl(string $anchor = '')
    {
        $anchor = !empty($anchor) ? '#' . $anchor : '';

        return $this->baseAddress . 'thermostat-planif' . $anchor;
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @return \Entity\ThermostatPlanif
     * @throws \Exception
     */
    protected function createThermostatPlanifFromPost(HTTPRequest $request): ThermostatPlanif
    {
        $thermostatPlanif = new ThermostatPlanif(
            [
                'jour' => $request->postData('jour'),
                'modeid' => $request->postData('modeid'),
                'defaultModeid' => $request->postData('defaultModeid'),
                'heure1Start' => $request->postData('heure1Start') ?? '07:00',
                'heure1Stop' => $request->postData('heure1Stop') ?? '23:00',
                'heure2Start' => $request->postData('heure2Start'),
                'heure2Stop' => $request->postData('heure2Stop'),
                'nomid' => $request->postData('nomid')
            ]
        );

        if ($request->getExists('id')) {
            $id = $request->getData('id');
            $thermostatPlanif->setId($id);
        }

        if ($thermostatPlanif->nomid()) {
            $thermostatPlanifNom = $this->manager->getNom($thermostatPlanif->nomid());
            $thermostatPlanif->setNom($thermostatPlanifNom);
        }

        return $thermostatPlanif;
    }
}
