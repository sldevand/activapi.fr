<?php

namespace App\Frontend\Modules\ThermostatPlanif;

use OCFram\Block;
use OCFram\Application;
use OCFram\HTTPRequest;
use Materialize\FormView;
use Materialize\Link\Link;
use OCFram\BackController;
use Entity\ThermostatPlanif;
use Materialize\WidgetFactory;
use Entity\ThermostatPlanifNom;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use FormBuilder\ThermostatPlanifNameFormBuilder;
use App\Frontend\Modules\ThermostatPlanif\Block\PlanifCardList;
use App\Frontend\Modules\ThermostatPlanif\Form\ThermostatPlanifFormHandler;

/**
 * Class ThermostatPlanifController
 * @package App\Frontend\Modules\ThermostatPlanif
 */
class ThermostatPlanifController extends BackController
{
    const DUPLICATE_FORM_VIEW_TEMPLATE = __DIR__ . '/Block/duplicateFormView.phtml';

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
        /** @var \Model\ThermostatManagerPDO $thermostatManager */
        $thermostatManager = $this->managers->getManagerOf('Thermostat');
        $thermostat = current($thermostatManager->getList());

        $thermostatPlanningsContainer = $this->manager->getListArray();
        $hideColumns = ['id', 'nomid', 'nom'];

        $planifCardList = new PlanifCardList($this->baseAddress);
        $cards = $planifCardList->create($thermostatPlanningsContainer, $hideColumns, $thermostat->planning());

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
            $this->deleteActionCache('index');
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
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executeEdit(HTTPRequest $request)
    {
        $domId = 'Edition';
        if (!$id = $request->getData('id')) {
            $domId = 'Ajout';
        }

        $this->page->addVar('title', "$domId du Scénario");

        $link = new Link(
            $domId,
            $this->baseAddress . "thermostat-planif",
            "arrow_back",
            "white-text",
            "white-text"
        );
        $submitButton = new FlatButton(
            [
                'id' => 'submit',
                'title' => 'Valider',
                'color' => 'primaryTextColor',
                'type' => 'submit',
                'icon' => 'check',
                'wrapper' => 'col s12'
            ]
        );

        $cardTitle = $link->getHtml();
        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $thermostatPlanif = $this->manager->getUnique($id);
        /** @var \Model\ThermostatModesManagerPDO $thermostatModesManager */
        $thermostatModesManager = $this->managers->getManagerOf('ThermostatModes');
        $modes = $thermostatModesManager->getList();
        
        $formBlock = $this->getBlock(__DIR__ . '/Block/thermostatPlanifForm.phtml', $thermostatPlanif, $modes, $submitButton);
        $card->addContent($formBlock);

        return $this->page->addVar('card', $card);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete(HTTPRequest $request)
    {
        if (!$id = $request->getData('id')) {
            $this->app()->user()->setFlash('No id was found in the request for deletion');
            $this->deleteActionCache('index');
            return $this->redirectBack();
        }

        if ($request->method() === HTTPRequest::POST) {
            if ($this->manager->delete($id)) {
                $this->deleteActionCache('index');
            }
            return $this->redirectBack();
        }

        $nom = $this->manager->getNom($id);
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
     * @param \OCFram\HTTPRequest $request
     * @throws \Exception
     */
    public function executeDuplicate(HTTPRequest $request)
    {
        if (!$id = $request->getData('id')) {
            $this->app()->user()->setFlash('No id was found in the request for duplication');
            $this->deleteActionCache('index');
            return $this->redirectBack();
        }

        if ($request->method() === HTTPRequest::POST) {
            try {
                $nom = $request->postData('nom');
                $this->deleteActionCache('index');
                $this->manager->duplicate($id, $nom);
            } catch (\Exception $exception) {
                $this->app()->user()->setFlash($exception->getMessage());
                return $this->app->httpResponse()->redirectReferer();
            }
            return $this->redirectBack();
        }

        $nom = $this->manager->getNom($id);
        $domId = 'Duplication';
        $backUrl = $this->baseAddress . 'thermostat-planif';
        $cardTitle = WidgetFactory::makeBackArrow($domId, $backUrl . '#' . $nom)->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->duplicateFormView($nom));

        $this->page->addVar('card', $card);
    }

    /**
     * @param string $anchor
     */
    protected function redirectBack(string $anchor = '')
    {
        $this->app->httpResponse()->redirect($this->getRouteUrl($anchor));
    }

    /**
     * @param array $args
     * @return false|string
     */
    public function duplicateFormView(...$args)
    {
        return Block::getTemplate(self::DUPLICATE_FORM_VIEW_TEMPLATE, ...$args);
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
