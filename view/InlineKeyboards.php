<?php

use Telegram\Bot\Keyboard\Keyboard;

class InlineKeyboards extends ViewWrapper {


  private function __construct() {}


  /**
   * 
   */
  public static function getNotifyStatus($raw_notify_status_data, $silent) {
    $inline_keyboard = [];
    foreach ($raw_notify_status_data as $notify_info) {
      $crypto_id = $notify_info['crypto_id'];
      $crypto_name = $notify_info['crypto_name'];
      $callback_query = "changeNotifyStatus" . $crypto_id;
      $status = $notify_info['notify_status'] ? "\xF0\x9F\x9F\xA2 on" : "\xF0\x9F\x94\xB4 off";

      array_push($inline_keyboard, [
        [
          "text" => "[ ".$crypto_id." ]  " .$crypto_name . ": " . $status,
          "callback_data" => $callback_query
        ]
      ]);

    }

    array_push($inline_keyboard, [
      [
        "text" => ($silent ? "Disabled notifications: yes \xF0\x9F\x94\x87" : "Disabled notifications: no \xF0\x9F\x94\x88"),
        "callback_data" => "changeSilentNotifications"
      ]
    ]);

    return self::createInlineKeyboard($inline_keyboard);
  }

  /**
   * {@inheritdoc}
   */
  protected static function createInlineKeyboard($inline_keyboard) {
    return Keyboard::make([
      'inline_keyboard' => $inline_keyboard,
    ]);
  }

}