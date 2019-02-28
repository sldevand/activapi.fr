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

        $datas = $jsonencode ? json_decode(json_encode($rawDatas), true) : (array)$rawDatas;

        $tableDatas = [];
        $headers = [];

        if(empty($datas[0])){
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
}
