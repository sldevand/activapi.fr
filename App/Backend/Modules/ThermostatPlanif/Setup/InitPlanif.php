<?php

namespace App\Backend\Modules\ThermostatPlanif\Setup;

use OCFram\Managers;
use OCFram\PDOFactory;
use Entity\Crontab\Crontab;
use Entity\ThermostatPlanif;
use Entity\ThermostatPlanifNom;
use SFram\Api\DataSetupInterface;

/**
 * Class InitPlanif
 * @package App\Backend\Modules\ThermostatPlanif\Setup
 */
class InitPlanif implements DataSetupInterface
{
    /** @var Managers */
    protected $managers;

    public function __construct()
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
    }

    public function execute()
    {
        /** @var \Model\ThermostatPlanifManagerPDO $thermostatPlanifManager */
        $thermostatPlanifManager = $this->managers->getManagerOf('ThermostatPlanif');
        $thermostatPlanif = new ThermostatPlanif(['nom' => $this->getThermostatPlanifNom()]);
        $thermostatPlanifManager->save($thermostatPlanif);
    }

    public function getThermostatPlanifNom() {
        return new ThermostatPlanifNom(['nom' => 'Quotidien']);
    }
}
