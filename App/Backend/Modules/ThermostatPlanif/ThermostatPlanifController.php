<?php

namespace App\Backend\Modules\ThermostatPlanif;

use Entity\ThermostatPlanif;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ThermostatPlanifController
 * @package App\Backend\Modules\ThermostatPlanif
 */
class ThermostatPlanifController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $thermostatPlanifs = $manager->getList($id);
        $this->page->addVar('thermostatPlanifs', $thermostatPlanifs);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeName(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $manager = $this->managers->getManagerOf('ThermostatPlanif');

        if ($request->getExists('id')) {
            $id = $request->getData('id');
        }

        if (!is_null($id) && !empty($id)) {
            $thermostatPlanifs = $manager->getNom($id);
        } else {
            $thermostatPlanifs = $manager->getNoms();
        }

        $this->page->addVar('thermostatPlanifs', $thermostatPlanifs);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeUpdate(HTTPRequest $request)
    {
        $hydrate = [
            'id' => $request->postData('id'),
            'nom' => $request->postData('nom'),
            'modeid' => $request->postData('modeid'),
            'sensorid' => $request->postData('sensorid'),
            'planning' => $request->postData('planning'),
            'manuel' => $request->postData('manuel'),
            'consigne' => $request->postData('consigne'),
            'delta' => $request->postData('delta')
        ];

        foreach ($hydrate as $key => $value) {
            if (is_null($value)) {
                $this->page->addVar('thermostatPlanif', "Update Error : Value " . $key . " is null!");
                return;
            }
        }

        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $thermostat = new ThermostatPlanif($hydrate);

        $manager->modify($thermostat);
        $this->page->addVar('thermostatPlanif', "Success");
    }
}
