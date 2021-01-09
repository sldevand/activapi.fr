<?php

namespace Helper\Pagination;

use OCFram\ApplicationComponent;
use OCFram\HTTPRequest;

/**
 * Class Data
 * @package Helper\Pagination
 */
class Data extends ApplicationComponent
{
    const PAGE_OFFSET = 3;

    /**
     * @param int $page
     * @param string $uri
     * @param int $count
     * @param int $totalPages
     * @return array
     */
    public function getPaginationPages(int $page, string $uri, int $count, int $totalPages)
    {
        $pages = [];

        $start = $page - self::PAGE_OFFSET;
        $end = $page + self::PAGE_OFFSET;

        if ($start <= 1) {
            $start = 1;
        }

        if ($end >= $totalPages) {
            $end = $totalPages;
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = $i == $page ? 'active' : 'waves-effect';
            $pages [] = [
                'name' => $i,
                'href' => $uri . '-' . $i . '-' . $count,
                'class' => $class
            ];
        }

        return $pages;
    }

    /**
     * @param HTTPRequest $request
     * @return string
     */
    public function getUri(HTTPRequest $request)
    {
        $uri = explode('-', $request->requestURI());

        while (count($uri) > 1) {
            array_pop($uri);
        }

        return implode('-', $uri);
    }
}
