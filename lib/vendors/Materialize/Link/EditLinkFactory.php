<?php

namespace Materialize\Link;

use OCFram\ApplicationComponent;

/**
 * Class EditLinkFactory
 * @package Materialize\Link
 */
class EditLinkFactory extends ApplicationComponent
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
            'edit',
            'primaryTextColor'
        );
    }
}
