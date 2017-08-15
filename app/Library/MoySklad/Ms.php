<?php
namespace App\Library\MoySklad;

use function GuzzleHttp\Psr7\build_query;

class Ms  {

    const USER = 'developer@fit2_u';
    const PASS = 'qw200386';
//    const USER = 'admin@fi2u_test'; // test
//    const PASS = '33553a82cc'; // test
    const STORE = '9bb42c33-1a46-4358-a7eb-0afe3ca07e04';  // Магазин (склад)   70cbdcd6-00f2-11e4-71a6-002590a28eca
    const TARGET = '4578c52c-dfdb-4ab1-85e8-d255d1337032'; //продавец.  70ca5412-00f2-11e4-3a70-002590a28eca
//    const STORE = '8a58d846-4afa-11e7-7a6c-d2a900002236';   // test
//    const TARGET = '8a578cc3-4afa-11e7-7a6c-d2a900002234';  // test

    public $apiUrl = "https://online.moysklad.ru/api/remap/1.1/";

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

    public function post_data($url_ms, $postData, $referer_ms = '')
    {
        $ch_ms     = curl_init();
        curl_setopt($ch_ms, CURLOPT_URL, $url_ms);
        curl_setopt($ch_ms, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch_ms, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch_ms, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch_ms, CURLOPT_CONNECTTIMEOUT, 5);// таймаут4
        curl_setopt($ch_ms, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        curl_setopt($ch_ms, CURLOPT_POST, true);
        curl_setopt($ch_ms, CURLOPT_POSTFIELDS, $postData);

        $headers_ms = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode(self::USER . ":" . self::PASS)
        );
        curl_setopt($ch_ms, CURLOPT_HTTPHEADER, $headers_ms);
        curl_setopt($ch_ms, CURLOPT_REFERER, $referer_ms);
        curl_setopt($ch_ms, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36");
        $result_ms = curl_exec($ch_ms);
        curl_close ($ch_ms);
        return $result_ms;
    }

    public function newAgent($postData){
        $res = $this->post_data($this->url('entity/counterparty'), $postData);
        return json_decode($res);
    }

    // Get products filtered by params
    public function importProducts($paramsString)
    {
        $res = $this->get_page($this->url('entity/assortment', $paramsString), self::USER, self::PASS);
        return json_decode($res);
    }

    public function getStock($paramsString)
    {
        $res = $this->get_page($this->url('report/stock/all', $paramsString), self::USER, self::PASS);
        return json_decode($res);
    }

    public function getAgents($paramString)
    {
        $res = $this->get_page($this->url('entity/counterparty', $paramString), self::USER, self::PASS);
        return json_decode($res);
    }

    // Get full url
    public function url($method, $paramsString = '')
    {
        if($paramsString)
            return $this->apiUrl . $method . "?" . $paramsString;
        else
            return $this->apiUrl . $method;
    }

    public function postOrder($postData)
    {
        $res = $this->post_data($this->url('entity/customerorder'), $postData);
        return json_decode($res);
    }

    public function lastOrder()
    {
        $paramsString = build_query([
            'limit' => 1,
            'order' => 'name,desc',
        ]);
        $res = $this->get_page($this->url('entity/customerorder', $paramsString), self::USER, self::PASS);
        return json_decode($res);
    }

    public function getOrderMeta()
    {
        $res = $this->get_page($this->url('entity/customerorder/metadata'), self::USER, self::PASS);
        return json_decode($res);
    }

    public function getOrderById($id)
    {
        $res = $this->get_page($this->url('entity/customerorder/'. $id), self::USER, self::PASS);
        return json_decode($res);
    }
}