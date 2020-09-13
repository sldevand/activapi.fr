<?php

namespace Materialize\Link;

use OCFram\ApplicationComponent;

class BackLinkFactory extends ApplicationComponent
{
    /**
     * @param string $domId
     * @param string $href
     * @return Link
     */
    public static function create(string $domId, string $href)
    {
        return new Link(
            $domId,
            $href,
            "arrow_back",
            "white-text",
            "white-text"
        );
    }
}
