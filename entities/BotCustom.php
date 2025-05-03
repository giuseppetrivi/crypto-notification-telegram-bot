<?php

use Telegram\Bot\Api;
use Telegram\Bot\TelegramResponse;


/**
 * Class to have some default behaviors in telegram bot api
 */
class BotCustom extends Api {

  /**
   * All parameters i want to be default, with setters and getters
   */

  private static $chat_id = null;
  private static $parse_mode = 'html';

  private static function getChatId() {
    return self::$chat_id;
  }
  public static function setChatId($chat_id) {
    self::$chat_id = $chat_id;
  }

  private static function getParseMode() {
    return self::$parse_mode;
  }
  public static function setParseMode($parse_mode) {
    self::$parse_mode = $parse_mode;
  }


  /**
   * Function to set custom default values for every post call of
   * telegram bot api
   */
  public function post(string $method, array $parameters=[], bool $file_upload=false): TelegramResponse {
    if (self::getChatId()!=null) {
      $parameters['chat_id'] = self::getChatId();
    }

    if (self::getParseMode()!=null) {
      $parameters['parse_mode'] = self::getParseMode();
    }

    return parent::post($method, $parameters, $file_upload);
  }

}