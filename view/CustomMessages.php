<?php

class CustomMessages {

  public const PRESS_BACK = "<i>Press Back to go to main menu</i>";


  public static function welcomeStart($username="nulla") {
    $text = "\xF0\x9F\x98\x8A Hi @" . $username . " !\n"
      . "Welcome in this tracker bot of main cryptocurrencies.\n\n"
      . "\xE2\x9A\x93 This below is the main menu";
    return $text;
  }

  public static function mainMenu() {
    $text = "\xE2\x9A\x93 Main menu";
    return $text;
  }


  public static function intervalInformations($actual_interval) {
    $text = "\xF0\x9F\x8E\xB2 Send the minutes of interval between notifications "  
      . "(or send one of standard from buttons below)\n\n"
      . "\xF0\x9F\x95\xA2 Actual interval = <code>$actual_interval</code> min.";
    return $text;
  }

  public static function notificationsInformations() {
    $text = "\xF0\x9F\x8C\x80 Select the option to change the notification settings "
      . "(from on to off and viceversa), or turn silent all notifications (or turn sound)";
    return $text;
  }

  public static function newMinutesIntervalInformations($new_minutes_interval) {
    $text = "\xF0\x9F\x95\xA2 <i>Now you will be updated every " . $new_minutes_interval
      . " minutes starting from now</i>";
    return $text;
  }


  public static function infoAboutCrypto($crypto_info) {
    $crypto_id = $crypto_info['id'];
    $crypto_name = $crypto_info['name'];
    $crypto_price = $crypto_info['price'];
    $crypto_percent_change_24h = $crypto_info['percent_change_24h'];


    $emojiPercent = $crypto_percent_change_24h>=0 ? "\xF0\x9F\x93\x88" : "\xF0\x9F\x93\x89";
    $text = "\xF0\x9F\xAA\x99 [ <b>".$crypto_id."</b> ] <b>".$crypto_name."</b>\n"
      . "\xF0\x9F\x92\xB6 Price: ".number_format($crypto_price, 8, ',', '.')." €\n"
      . "$emojiPercent Percentage in last 24h: <u>". ($crypto_percent_change_24h>0 ? "+" : "") .number_format($crypto_percent_change_24h, 3, ',', '.')." %</u>\n";
      //. " \xF0\x9F\x93\x85 Last-Updated: <i>".$lastUpdated."</i>\n\n";
    return $text;
  } 

}