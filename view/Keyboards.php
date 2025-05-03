<?php

use Telegram\Bot\Keyboard\Keyboard;

class Keyboards extends ViewWrapper {

  public const MAIN_MENU = [
    [MenuOptions::SET_TIME_INTERVAL, MenuOptions::NOTIFY_CENTER]
  ];

  public const SET_TIME_INTERVAL = [
    ["5", "10", "15", "20"],
    ["30", "45", "60", "90"],
    [MenuOptions::BACK]
  ];

  public const ONLY_BACK = [
    [MenuOptions::BACK]
  ];


  private function __construct() {}


  /**
   * {@inheritdoc}
   */
  protected static function createKeyboard($inline_keyboard) {
    return Keyboard::make([
      'keyboard' => $inline_keyboard,
      'resize_keyboard' => true
    ]);
  }  

}