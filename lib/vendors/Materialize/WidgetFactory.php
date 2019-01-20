<?php

namespace Materialize;

use Materialize\Card\Card;

/**
 * Class WidgetFactory
 * @package Materialize
 */
class WidgetFactory
{
    /**
     * @param $domId
     * @param $cardTitle
     * @param array $cardContents
     * @return Card
     */
    public static function makeCard($domId, $cardTitle)
    {

        $cardOpt = [
            'id' => $domId,
            'bgColor' => 'primaryLightColor',
            'textColor' => 'textOnPrimaryColor',
            'title' => $cardTitle];

        return new Card($cardOpt);
    }

    /**
     * @param $domId
     * @param $rawDatas
     * @param bool $jsonencode
     * @param array $hideColumns
     * @return Table
     */
    public static function makeTable($domId, $rawDatas, $jsonencode = true, $hideColumns = [])
    {
        if ($jsonencode) {
            $datas = json_decode(json_encode($rawDatas), TRUE);
        } else {
            $datas = (array)$rawDatas;
        }

        $tableDatas = [];
        $headers = [];

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
}
