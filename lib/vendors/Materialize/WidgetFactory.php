<?php

namespace Materialize;

use Materialize\Card\Card;
use Materialize\Pagination\Pagination;
use Materialize\Spinner\Spinner;

/**
 * Class WidgetFactory
 * @package Materialize
 */
class WidgetFactory
{
    /**
     * @param array $pages
     * @param int $logsCount
     * @param int $page
     * @param string $baseHref
     * @param int $pagesCount
     * @return Pagination
     */
    public static function makePagination(array $pages, int $logsCount, int $page, string $baseHref, int $pagesCount)
    {
        $classPrev = $page <= 1 ? 'disabled' : 'waves-effect';
        $classNext = $page >= $pagesCount ? 'disabled' : 'waves-effect';

        $paginationData = [
            'pages'     => $pages,
            'hrefPrev'  => $baseHref . '-' . 1 . '-' . $logsCount,
            'classPrev' => $classPrev,
            'hrefNext'  => $baseHref . '-' . $pagesCount . '-' . $logsCount,
            'classNext' => $classNext,
        ];

        return new Pagination($paginationData);
    }

    /**
     * @param string $domId
     * @param string $cardTitle
     * @param string $content
     * @return Card
     */
    public static function makeCard(string $domId, string $cardTitle, string $content = '')
    {
        $cardOpt = [
            'id'        => $domId,
            'bgColor'   => 'primaryLightColor',
            'textColor' => 'textOnPrimaryColor',
            'title'     => $cardTitle,
            'contents'  => [$content]
        ];

        return new Card($cardOpt);
    }

    /**
     * @param string $domId
     * @param array $rawDatas
     * @param bool $jsonencode
     * @param array $hideColumns
     * @return Table
     */
    public static function makeTable(string $domId, array $rawDatas, bool $jsonencode = true, array $hideColumns = [])
    {
        $datas = $jsonencode ? json_decode(json_encode($rawDatas), true) : (array)$rawDatas;

        $tableDatas = [];
        $headers = [];

        if (empty($datas[0])) {
            return new Table([]);
        }

        foreach ($datas[0] as $key => $data) {
            $headers[] = $key;
        }

        foreach ($datas as $key => $data) {
            $tableDatas[] = $data;
        }

        return new Table([
            'id' => 'table' . $domId,
            'datas' => $tableDatas,
            'headers' => $headers,
            'hideColumns' => $hideColumns
        ]);
    }

    /**
     * @param string $domId
     * @return Spinner
     */
    public static function makeSpinner(string $domId)
    {
        return new Spinner(
            [
                'id' => $domId
            ]
        );
    }
}
