<?php

namespace App\Http\Controllers;
//https://github.com/absolutehh/laravel-curl
class RetailCRMController extends Controller
{
  //
  public function product($id) {
    $filters = [
        'ids' => [$id],
    ];
    $url ='';
    //$response = \Curl::get($url, $headers = [], $encoding = 'JSON');
    if( $curl = curl_init() ) {
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
      $response = curl_exec($curl);
      curl_close($curl);
    }
    return $response;
  }
}
