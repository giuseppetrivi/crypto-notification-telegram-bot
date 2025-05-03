<?php

/**
 * Class to handle the static table of cryptocurrencies list
 */
class Cryptocurrencies extends Entity {

  private function __construct() {}

  public static function getAllCryptocurrencies() {
    $cryptocurrencies = DB::query("SELECT * FROM cryn_cryptocurrencies");

    if (!empty($cryptocurrencies)) {
      return $cryptocurrencies;
    }

    throw new Exception("Error in cryptocurrencies select");
  }


  public static function getCommaOfNamecodes($crypto_list) {
    $commalist_cryto_id = '';
    foreach ($crypto_list as $crypto) {
      $commalist_cryto_id .= ','.$crypto['crypto_id'];
    }
    return substr($commalist_cryto_id, 1);
  }

}