<?php

namespace App\Http\Controllers;
use App\Models\Setting;

class RetailCRMController extends Controller
{
  //
  protected $api_key;
  protected $url;
  public function  __construct(){
    $this->api_key = env('RETAIL_CRM_API_KEY');
    $setting = Setting::where('var', 'retailcrm_url')->first()->value;
    $this->api_url = $setting.'/api/v5/';
  }
  //get product info
  public function product($id) {
    $filter = [
        'externalId' => $id,
    ];
    $queryData = [
        'apiKey' => $this->api_key,
        'filter' => $filter,
    ];
    $queryString = http_build_query($queryData);
    $url = $this->api_url.'store/products?'.$queryString;
    if( $curl = curl_init() ) {
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
      $response = curl_exec($curl);
      curl_close($curl);
    }
    return response()->json(json_decode($response));
  }
  /*
   * @param id - order externalId for filter, if not use, take all id
   * @return orders info
   * **/
  public function orders($id = null) {
    $filter = [
    ];
    if(isset($id)) {
      $filter['externalIds'] = [$id];
    }
    $queryData = [
        'apiKey' => $this->api_key,
        'filter' => $filter,
    ];
    $queryString = http_build_query($queryData);
    $url = $this->api_url.'orders?'.$queryString;
    if( $curl = curl_init() ) {
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
      $response = curl_exec($curl);
      curl_close($curl);
    }
    return response()->json(json_decode($response));
  }
}
