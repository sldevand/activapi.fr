<?php

namespace App\Backend\Modules\ThermostatModes;

use Entity\ThermostatMode;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ThermostatModesController
 * @package App\Backend\Modules\ThermostatModes
 */
class ThermostatModesController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');


        if ($request->getExists('id')) {
            $id = $request->getData('id');
        }

        if (!is_null($id) && !empty($id)) {
            $modes = $manager->getUnique($id);
        } else {
            $modes = $manager->getList();
        }
        $this->page->addVar('modes', $modes);
    }

    public function executeDelete(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('ThermostatModes');

        $delete = ["error" => 'Rien à supprimer!'];
        if ($request->method() == 'POST') {
            if ($request->getExists('id')) {
                $id = $request->getData('id');
                $delete = $manager->delete($id);
            }
        }

        $this->page->addVar('delete', $delete);
    }

    public function executeEdit(HTTPRequest $request)
    {

        $manager = $this->managers->getManagerOf('ThermostatModes');
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
                $item = new ThermostatMode();
            }
        }

        $this->page->addVar('cards', $item);
    }
}
