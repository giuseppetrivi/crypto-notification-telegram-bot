<?php

/**
 * Class to handle the results of Coinmarket api calls
 */
class CoinmarketResponseHandler extends Entity {

  private $response_json;

  public function __construct($response_json) {
    $this->response_json = $response_json;
  }


  public function getResponseArray() {
    return json_decode($this->response_json, true);
  }

  public function responseHasErrors() {
    $response_array = $this->getResponseArray();

    if (array_key_exists('status', $response_array)) {
      $status = $response_array['status'];
      if (array_key_exists('error_code', $status) && array_key_exists('error_message', $status)) {
        $error_code = $status['error_code'];
        $error_message = $status['error_message'];
        if ($error_code!=0 && $error_message!="") {
          return true;
        }
        else {
          return false;
        }
      }
    }
    return false;
  }


  public function getPriceFromCryptoId($crypto_id) {
    $response_array = $this->getResponseArray();

    if (!array_key_exists('data', $response_array)) {
      //error
    }
    $data = $response_array['data'];
    if (!array_key_exists($crypto_id, $data)) {
      //error
    }
    $crypto_info = $data[$crypto_id];
    if (!array_key_exists('quote', $crypto_info)) {
      //error
    }
    $quote = $crypto_info['quote'];
    if (!array_key_exists('EUR', $quote)) {
      //error
    }
    $eur_info = $quote['EUR'];
    if (!array_key_exists('price', $eur_info)) {
      //error
    }
    return $eur_info['price'];
  }

  public function getPercentChange24hFromCryptoId($crypto_id) {
    $response_array = $this->getResponseArray();

    if (!array_key_exists('data', $response_array)) {
      //error
    }
    $data = $response_array['data'];
    if (!array_key_exists($crypto_id, $data)) {
      //error
    }
    $crypto_info = $data[$crypto_id];
    if (!array_key_exists('quote', $crypto_info)) {
      //error
    }
    $quote = $crypto_info['quote'];
    if (!array_key_exists('EUR', $quote)) {
      //error
    }
    $eur_info = $quote['EUR'];
    if (!array_key_exists('percent_change_24h', $eur_info)) {
      //error
    }
    return $eur_info['percent_change_24h'];
  }

}