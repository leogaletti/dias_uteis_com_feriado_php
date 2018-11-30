<?php
/**
 * Created by PhpStorm.
 * User: leonardomello
 * Date: 30/11/2018
 * Time: 13:34
 */
ini_set('allow_url_fopen',1);
require 'simple_html_dom.php';

// Create DOM from URL or file

$html2018 = file_get_html('http://www.anbima.com.br/feriados/fer_nacionais/2018.asp');
$html2019 = file_get_html('http://www.anbima.com.br/feriados/fer_nacionais/2019.asp');

$dates = [];

foreach($html2018->find('#cinza50 > div > table > tbody > tr > td > table > tbody > tr') as $element) {
    $td = $element->find("td", 0)->plaintext;
    if ($td != "Data") {
        $arrayDate = explode("/", $td);
        $day = str_pad($arrayDate[0], 2, '0', STR_PAD_LEFT);
        $month = str_pad($arrayDate[1], 2, '0', STR_PAD_LEFT);
        $actualDate = new DateTime($month . "/" . $day . "/" . $arrayDate[2]);

        $dates[$actualDate->format("d/m/Y")] = $actualDate->format("d/m/Y");
    }
}

foreach($html2019->find('#cinza50 > div > table > tbody > tr > td > table > tbody > tr') as $element) {
    $td = $element->find("td", 0)->plaintext;
    if ($td != "Data") {
        $arrayDate = explode("/", $td);
        $day = str_pad($arrayDate[0], 2, '0', STR_PAD_LEFT);
        $month = str_pad($arrayDate[1], 2, '0', STR_PAD_LEFT);
        $actualDate = new DateTime($month . "/" . $day . "/" . $arrayDate[2]);

        $dates[$actualDate->format("d/m/Y")] = $actualDate->format("d/m/Y");
    }
}

$dataGet = $_GET;

if (!empty($dataGet['start']) && !empty($dataGet['end'])) {
    $begin = new DateTime($dataGet['start']);
    $end = new DateTime($dataGet['end']);

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);

    $count = 0;
    foreach ($period as $dt) {
        $dayWeek = $dt->format("w");
        if ($dayWeek != 0)
            if (!array_key_exists($dt->format("d/m/Y"), $dates))
                $count++;
    }
    header("Content-Type: application/json");
    echo json_encode(["days" => $count]);
    die();
}