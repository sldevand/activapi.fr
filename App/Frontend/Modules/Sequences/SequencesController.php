<?php

namespace App\Frontend\Modules\Sequences;

use App\Backend\Modules\Sequences\SequencesController as SequencesBackController;
use App\Frontend\Modules\FormView;
use Materialize\Button\FlatButton;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\WidgetFactory;
use OCFram\HTTPRequest;

/**
 * Class SequencesController
 * @package App\Frontend\Modules\Sequences
 */
class SequencesController extends SequencesBackController
{
    use FormView;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $sequences = parent::executeGet($request);

        $this->page->addVar('title', 'Gestion des sequences');

        $cards = [];
        $cards[] = $this->makeSequencesWidget($sequences);
        $addSequenceFab = new FloatingActionButton([
            'id' => "addSequenceFab",
            'fixed' => true,
            'icon' => "add",
            'href' => "../activapi.fr/sequences-add"
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
        foreach ($sequences as $scenario) {
            $linkEdit = new Link(
                '',
                "../activapi.fr/sequences-edit-" . $scenario["id"],
                'edit',
                'primaryTextColor'
            );
            $linkDelete = new Link(
                '',
                "../activapi.fr/sequences-delete-" . $scenario["id"],
                'delete',
                'secondaryTextColor'
            );
            $scenario["editer"] = $linkEdit->getHtmlForTable();
            $scenario["supprimer"] = $linkDelete->getHtmlForTable();
            $sequencesData[] = $scenario;
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
        $manager = $this->getSequencesManager();

        $domId = 'Suppression';
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $manager->delete($id);
                $this->app->httpResponse()->redirect('../activapi.fr/sequences');
            }
        }

        $link = new Link(
            $domId,
            "../activapi.fr/sequences",
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
            "../activapi.fr/sequences",
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
