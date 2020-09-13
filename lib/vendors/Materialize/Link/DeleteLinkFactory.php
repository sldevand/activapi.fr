<?php

namespace Materialize\Link;

use OCFram\ApplicationComponent;

/**
 * Class DeleteLinkFactory
 * @package Materialize\Link
 */
class DeleteLinkFactory extends ApplicationComponent
{
    /**
     * @param string $href
     * @return Link
     */
    public static function create(string $href)
    {
        return new Link(
            '',
            $href,
            'delete',
            'secondaryTextColor'
        );
    }
}
