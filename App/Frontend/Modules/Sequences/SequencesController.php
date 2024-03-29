<?php

namespace App\Frontend\Modules\Sequences;

use Exception;
use OCFram\Application;
use OCFram\HTTPRequest;
use Materialize\FormView;
use Materialize\Link\Link;
use Materialize\WidgetFactory;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use Model\Scenario\SequencesManagerPDO;
use App\Frontend\Modules\Scenarios\AbstractScenariosController;

/**
 * Class SequencesController
 * @package App\Frontend\Modules\Sequences
 */
class SequencesController extends AbstractScenariosController
{
    use FormView;

    protected SequencesManagerPDO $sequencesManager;

    public function __construct(Application $app, string $module, string $action) {
        parent::__construct($app, $module, $action);
        $this->sequencesManager = $this->scenarioManagerPDOFactory->getSequencesManager();
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        try {
            $sequences = $this->sequencesManager->getAll();
        } catch (Exception $exception) {
            $sequences = [];
        }
       
        $this->page->addVar('title', 'Gestion des sequences');

        $cards = [];
        $cards[] = $this->makeSequencesWidget($sequences);
        $addSequenceFab = new FloatingActionButton([
            'id' => "addSequenceFab",
            'fixed' => true,
            'icon' => "add",
            'href' => $this->baseAddress . "sequences-add"
        ]);

        $this->page->addVar('cards', $cards);
        $this->page->addVar('addSequenceFab', $addSequenceFab);
    }

    /**
     * @param array $sequences
     * @return \Materialize\Card\Card
     */
    public function makeSequencesWidget($sequences)
    {
        $domId = 'Sequences';
        $sequences = json_decode(json_encode($sequences), true);
        $card = WidgetFactory::makeCard($domId, $domId);
        if (!$sequences) {
            $card->addContent('Pas de sequences');
            return $card;
        }
        $table = $this->createSequencesTable($sequences, 'sequences-table');
        $card->addContent($table->getHtml());

        return $card;
    }

    /**
     * @param array $sequences
     * @param string $domId
     * @return \Materialize\Table
     */
    public function createSequencesTable($sequences, $domId)
    {
        $sequencesData = [];
        foreach ($sequences as $sequence) {
            $linkEdit = new Link(
                '',
                $this->baseAddress . "sequences-edit-" . $sequence["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                $this->baseAddress . "sequences-delete-" . $sequence["id"],
                'delete',
                'secondaryTextColor'
            );
            $sequence["editer"] = $linkEdit->getHtmlForTable();
            $sequence["supprimer"] = $linkDelete->getHtmlForTable();
            $sequencesData[] = $sequence;
        }

        $hideColumns = ['data', 'actions'];

        return WidgetFactory::makeTable($domId, $sequencesData, true, $hideColumns);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeDelete($request)
    {
        $domId = 'Suppression';
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $this->sequencesManager->delete($id);
                $this->deleteActionCache('index');
                $this->app->httpResponse()->redirect($this->baseAddress . 'sequences');
            }
        }

        $link = new Link(
            $domId,
            $this->baseAddress . "sequences",
            "arrow_back",
            "white-text",
            "white-text"
        );

        $cardTitle = $link->getHtml();

        $card = WidgetFactory::makeCard($domId, $cardTitle);
        $card->addContent($this->deleteFormView());

        $this->page->addVar('title', "Suppression de la Séquence");
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

        $this->page->addVar('title', "$domId de la Séquence");

        $link = new Link(
            $domId,
            $this->baseAddress . "sequences",
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
        $formBlock = $this->getBlock(__DIR__ . '/Block/sequencesForm.phtml', $id, $submitButton);
        $card->addContent($formBlock);
        $cards = [];
        $cards[] = $card;

        return $this->page->addVar('cards', $cards);
    }
}
