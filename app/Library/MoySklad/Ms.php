<?php
namespace App\Library\MoySklad;

class Ms  {

    const USER = 'admin@fi2u_test'; //'developer@fit2_u';
    const PASS = '33553a82cc'; //'qw200386';
    const STORE = '8a58d846-4afa-11e7-7a6c-d2a900002236'; //'9bb42c33-1a46-4358-a7eb-0afe3ca07e04';  // Магазин (склад)   70cbdcd6-00f2-11e4-71a6-002590a28eca
    const TARGET = '8a578cc3-4afa-11e7-7a6c-d2a900002234'; //'4578c52c-dfdb-4ab1-85e8-d255d1337032'; //продавец.  70ca5412-00f2-11e4-3a70-002590a28eca

    public function get_page($url_ms, $user_ms = '', $pass_ms = '', $referer_ms = '', $cookie_file_ms = '', $post_ms = '', $set_cookie_ms = false) {
        $ch_ms     = curl_init();
        curl_setopt($ch_ms, CURLOPT_URL, $url_ms);
        curl_setopt($ch_ms, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch_ms, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch_ms, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch_ms, CURLOPT_CONNECTTIMEOUT, 5);// таймаут4
        curl_setopt($ch_ms, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "goodId=600ea501-927e-11e3-957c-002590a28eca");
        $headers_ms = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode("$user_ms:$pass_ms")
        );
        curl_setopt($ch_ms, CURLOPT_HTTPHEADER, $headers_ms);
        curl_setopt($ch_ms, CURLOPT_REFERER, $referer_ms);
        curl_setopt($ch_ms, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36");
        $result_ms = curl_exec($ch_ms);
        curl_close ($ch_ms);
        return $result_ms;
    }

    public function newAgent($name_ms,$address_ms='',$phone_ms='',$email_ms=''){

        $body_ms = '<?xml version="1.0" encoding="UTF-8"?>
        <company payerVat="true" companyType="URLI" discount="0.0" autoDiscount="0.0" discountCorrection="0.0" archived="false" name="'.$name_ms.'">
        <requisite actualAddress="'.$address_ms.'"/>
        <contact address="" phones="'.$phone_ms.'" mobiles="" email="'.$email_ms.'"/>
        <tags>
        <tag>сайт</tag>
        </tags>
        </company>';

        $sock_ms = fsockopen("ssl://online.moysklad.ru", 443, $errno_ms, $errstr_ms, 30);

        if (!$sock_ms) die("$errstr_ms ($errno_ms)\n");

        fputs($sock_ms, "PUT /exchange/rest/ms/xml/Company HTTP/1.1\r\n");
        fputs($sock_ms, "Host: online.moysklad.ru\r\n");
        fputs($sock_ms, "Authorization: Basic " . base64_encode(self::USER.':'.self::PASS) . "\r\n");
        fputs($sock_ms, "Content-Type: application/xml \r\n");
        fputs($sock_ms, "Accept: */*\r\n");
        fputs($sock_ms, "Content-Length: ".strlen($body_ms)."\r\n");
        fputs($sock_ms, "Connection: close\r\n\r\n");
        fputs($sock_ms, "$body_ms");

        while ($str_ms = trim(fgets($sock_ms, 4096)));

        $body_ms = "";

        while (!feof($sock_ms)){
            $body_ms.= fgets($sock_ms, 4096);
        }
        fclose($sock_ms);

        $xml_ms = simplexml_load_string($body_ms);
        $json_ms = json_encode($xml_ms);
        $array_ms = json_decode($json_ms,TRUE);

        return $array_ms["uuid"];
    }

    // Get products filtered by params
    public function importProducts($paramsString)
    {
//        $url = "https://online.moysklad.ru/api/remap/1.1/entity/variant?$paramsString";
        $url = "https://online.moysklad.ru/api/remap/1.1/report/stock/all?$paramsString";
//        $url = "https://online.moysklad.ru/api/remap/1.1/entity/product?$paramsString";
        $res = $this->get_page($url, Ms::USER, Ms::PASS);
        return json_decode($res);
    }

}