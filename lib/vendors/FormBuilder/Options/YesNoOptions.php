<?php

namespace FormBuilder\Options;

/**
 * Class YesNoOptions
 * @package FormBuilder\Options
 */
class YesNoOptions implements FormOptionsInterface
{
    /**
     * @return array
     */
    public static function toArray()
    {
        return [
            0 => 'Non',
            1 => 'Oui'
        ];
    }
}
