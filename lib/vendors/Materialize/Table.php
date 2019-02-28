<?php

namespace Materialize;

class Table extends Widget
{

    protected $datas = [];
    protected $headers = [];
    protected $hideColumns = [];

    public function getHtml()
    {

        if(empty($this->datas)){
            return '<span>Pas de données!</span>';
        }

        $prepHtml = '<table id="' . $this->id() . '"  class="bordered striped responsive-table">';
        $prepHtml .= '<thead><tr>';
        foreach ($this->headers as $header) {
            if (!in_array($header, $this->hideColumns)) {
                $prepHtml .= "<th>$header</th>";
            }
        }
        $prepHtml .= '</tr></thead>';
        $prepHtml .= '<tbody>';
        foreach ($this->datas as $data) {
            $prepHtml .= '<tr>';
            foreach ($data as $header => $value) {
                if (!in_array($header, $this->hideColumns)) {
                    $prepHtml .= "<td>";
                    if (is_string($value) || is_numeric($value)) {
                        $prepHtml .= $value;
                    } elseif (is_array($value)) {
                        foreach ($value as $elt) {
                            $prepHtml .= $elt . '<br>';
                        }
                    }
                    $prepHtml .= "</td>";
                }
            }
            $prepHtml .= '</tr>';
        }

        $prepHtml .= '</tbody>';
        $prepHtml .= '</table>';

        return $prepHtml;
    }

    //GETTERS
    public function datas()
    {
        return $this->datas;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function hideColumns()
    {
        return $this->hideColumns;
    }

    //SETTERS
    public function setDatas($datas)
    {
        $this->datas = $datas;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function setHideColumns($hideColumns)
    {
        $this->hideColumns = $hideColumns;
    }
}
