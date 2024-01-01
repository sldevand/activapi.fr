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
     * @return bool
     * @throws Exception
     */
    public function canSendPwrOnEmail(Thermostat $thermostat, int $delay)
    {
        $diff = DateFactory::diffMinutesFromStr('now', $thermostat->getLastPwrOff());

        return $delay - $diff < 0 && !$thermostat->isMailSent();
    }
}
