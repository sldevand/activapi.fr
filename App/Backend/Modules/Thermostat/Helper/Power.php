<?php

namespace App\Backend\Modules\Thermostat\Helper;

use Entity\Thermostat;
use Exception;
use OCFram\DateFactory;

/**
 * Class Power
 * @package App\Backend\Modules\Thermostat\Helper
 */
class Power
{
    /**
     * @param Thermostat $thermostat
     * @param int $delay
     * @return bool
     * @throws Exception
     */
    public function canTurnPwrOn(Thermostat $thermostat, int $delay)
    {
        $diff = DateFactory::diffMinutesFromStr(DateFactory::todayFullString(), $thermostat->getLastPwrOff());

        return $delay - $diff < 0;
    }
}
