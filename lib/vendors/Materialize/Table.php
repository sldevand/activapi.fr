<?php

namespace Materialize;

/**
 * Class Table
 * @package Materialize
 */
class Table extends Widget
{

    /**
     * @var array $datas
     */
    protected $datas = [];

    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @var array $hideColumns
     */
    protected $hideColumns = [];

    /**
     * @return string
     */
    public function getHtml()
    {

        if (empty($this->datas)) {
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


    /**
     * @return array
     */
    public function datas()
    {
        return $this->datas;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function hideColumns()
    {
        return $this->hideColumns;
    }

    /**
     * @param array $datas
     * @return $this
     */
    public function setDatas($datas)
    {
        $this->datas = $datas;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $hideColumns
     * @return $this
     */
    public function setHideColumns($hideColumns)
    {
        $this->hideColumns = $hideColumns;

        return $this;
    }
}
