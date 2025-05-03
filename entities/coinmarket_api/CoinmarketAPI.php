<?php

/**
 * Interface with CoinmarketAPI endpoints call
 */
class CoinmarketAPI {

  private $coinmarket_api_token = null;


  public function __construct() {
    $_SystemConfig = ConfigurationInfo::getInstance();
    $this->coinmarket_api_token = $_SystemConfig->getCoinmarketApiToken();
  }


  /**
   * 
   */
  public function getLatestQuotes() {
    $request_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';

    //ritorna i valori delle valute presenti nel database in valuta Euro
    $crypto_list = Cryptocurrencies::getAllCryptocurrencies();
    $commalist_crypto_id = Cryptocurrencies::getCommaOfNamecodes($crypto_list);
    $parameters = [
      'symbol' => $commalist_crypto_id,
      'convert' => 'EUR'
    ];
  
    $headers = [
      'Accepts: application/json',
      'X-CMC_PRO_API_KEY: '.$this->coinmarket_api_token
    ];
    $qs = http_build_query($parameters); // query string encode the parameters
    $request = "{$request_url}?{$qs}"; // create the request URL
  
    $curl = curl_init(); // Get cURL resource
    // Set cURL options
    curl_setopt_array($curl, array(
      CURLOPT_URL => $request,            // set the request URL
      CURLOPT_HTTPHEADER => $headers,     // set the headers 
      CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
    ));
  
    $response_json = curl_exec($curl); // Send the request, save the response
    curl_close($curl); // Close request
    return $response_json;
  }

}