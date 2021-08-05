<?php

namespace SFram\Helpers;

use DateTime;
use Exception;

/**
 * Class DateHelper
 * @package SFram\Helpers
 */
class DateHelper
{
    /**
     * @return string
     * @throws Exception
     */
    public static function now()
    {
        return (new DateTime())->format('Y-m-d H:i:s');
    }
}
