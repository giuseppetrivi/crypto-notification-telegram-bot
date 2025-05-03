<?php


/**
 * Singleton class to handle configuration info.
 * It has to be first setted (setInstance), with constructor parameter
 * and then to be getted.
 */
class ConfigurationInfo {
  private static $_Instance = null;

  private bool $testing;

  private function __construct($testing=false) {
    $this->testing = $testing;
  }

  public static function setInstance($testing=false) {
    if (self::$_Instance==null) {
      self::$_Instance = new ConfigurationInfo($testing);
    }
    return self::$_Instance;
  }

  public static function getInstance() {
    if (self::$_Instance==null) {
      throw new Exception("Errore nella configurazione");
    }
    return self::$_Instance;
  }

  /**
   * Takes the data in config file and convert in associative array
   */
  private function getConfigurationFileContent() {
    $mode = $this->testing ? 'testing' : 'production';
    $file_content = file_get_contents(__DIR__."/actual_config.json");
    if (!$file_content) {
      throw new Exception("Qualcosa è andato storto nella configurazione");
    }
    return json_decode($file_content, true)[$mode];
  }


  /* Database info */
  public function getDbUsername() {
    return self::getConfigurationFileContent()['DATABASE_INFO']['username'];
  }
  public function getDbPassword() {
    return self::getConfigurationFileContent()['DATABASE_INFO']['password'];
  }
  public function getDbName() {
    return self::getConfigurationFileContent()['DATABASE_INFO']['db_name'];
  }
  public function getDbHost() {
    return self::getConfigurationFileContent()['DATABASE_INFO']['db_host'];
  }


  /* Telegram bot API tokens */
  public function getTelegramBotApiToken() {
    return self::getConfigurationFileContent()['TELEGRAM_BOT_API_TOKEN'];
  }


  /* Coinmarket API token */
  public function getCoinmarketApiToken() {
    return self::getConfigurationFileContent()['COINMARKET_API_TOKEN'];
  }


  /* Open access to bot */
  public function getOpenAccessToBot() {
    return self::getConfigurationFileContent()['OPEN_ACCESS_TO_BOT'];
  }

}