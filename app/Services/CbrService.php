<?php

namespace App\Services;

use DOMDocument;

class CbrService {

    protected array $list = array();
    protected string $urlCbr = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';

    public function __construct() {
    }

    private function loadCurrency($date = null) {

        if ($date == null) {
            $dateStr = date('d/m/Y');
        } else {
            $dateStr = $date->format('d/m/Y');
        }

        $url = $this->urlCbr.$dateStr;

        $xml = new DOMDocument();


        if (@$xml->load($url)) {
            $this->list = [];

            $root = $xml->documentElement;
            $items = $root->getElementsByTagName('Valute');

            $i = 0;
            foreach ($items as $item) {
                $valuteId = $item->attributes->item(0)->nodeValue;
                $numCode = $item->getElementsByTagName('NumCode')->item(0)->nodeValue;
                $nominal = $item->getElementsByTagName('Nominal')->item(0)->nodeValue;
                $curs = $item->getElementsByTagName('Value')->item(0)->nodeValue;
                $charCode = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
                $name = $item->getElementsByTagName('Name')->item(0)->nodeValue;

                $array = [
                    'valuteID' => $valuteId,
                    'numCode' => $numCode,
                    'сharCode' => $charCode,
                    'name' => $name,
                    'nominal' => $nominal,
                    'value' => floatval(str_replace(',', '.', $curs)),
                    'date' => $date,
                ];

                $this->list[$i] = $array;
                $i++;
            }

            return true;
        } else {
            return false;
        }
    }

    public function getList($dataStart, $dataEnd): array {
        $dateDiff = $dataStart->diff($dataEnd)->days;

        $result = [];
        while ($dateDiff >= 0) {
            $newDate = clone($dataEnd);
            $newDate->modify("- {$dateDiff} day");
            $this->list = [];
            if (!$this->loadCurrency($newDate)) {
                // exception cbr
                return [];
            } else {
                $result = array_merge($result, $this->list);
            }
            $dateDiff--;
        }
        return $result;
    }

}
