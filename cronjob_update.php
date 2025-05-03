<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/business_core_autoloader.php';


/* Object for the base configuration infos */
$_SystemConfig = ConfigurationInfo::setInstance();

DB::$user = $_SystemConfig->getDbUsername();
DB::$password = $_SystemConfig->getDbPassword();
DB::$dbName = $_SystemConfig->getDbName();
//DB::$host = $_SystemConfig->getDbHost();


/* Starting bot and check to base objects */
$telegram_bot_api_token = $_SystemConfig->getTelegramBotApiToken();
$_Bot = new BotCustom($telegram_bot_api_token);

try {
  Cryptocurrencies::getAllCryptocurrencies();

  $_CoinmarketAPI = new CoinmarketAPI();
  $_CoinmarketResponse = new CoinmarketResponseHandler($_CoinmarketAPI->getLatestQuotes());
  
  /* TODO: save the result into database */

  $all_notifications_info = NotificationHandler::getAllNotificationsSettings();

  /* Loop to index all records in a sub-array foreach user */
  $info_foreach_user = [];
  $user = null;
  foreach ($all_notifications_info as $record) {
    $id_telegram = $record['user_idtelegram'];
    if ($user==null || $user!=$id_telegram) {
      $user = $id_telegram;
      $info_foreach_user[$user] = [];
    }
    array_push($info_foreach_user[$user], $record);
  }

  if (empty($info_foreach_user)) {
    throw new Exception("Errore nella catalogazione degli utenti");
  }

  /* Loop to create message and send it */
  foreach ($info_foreach_user as $id_telegram => $user_info) {
    
    $_User = new User($id_telegram);

    if ($_User->getTimeleftNotify()==0) {
      
      $final_message_to_send = "";
      foreach ($user_info as $notify_info) {
        $crypto_id = $notify_info['crypto_id'];
        $crypto_name = $notify_info['crypto_name'];
        $crypto_price = $_CoinmarketResponse->getPriceFromCryptoId($crypto_id);
        $crypto_percent_change_last_24h =  $_CoinmarketResponse->getPercentChange24hFromCryptoId($crypto_id);
        
        $crypto_info = [
          'id' => $crypto_id,
          'name' => $crypto_name,
          'price' => $crypto_price,
          'percent_change_24h' => $crypto_percent_change_last_24h
        ];
  
        $final_message_to_send .= CustomMessages::infoAboutCrypto($crypto_info)."\n";
      }
  
      if ($final_message_to_send!="" && $_User->getProcessHandler()->getProcessName()==null) {
        $silent_notify = $_User->getSilentNotify()==1 ? true : false;
        $_Bot->sendMessage([
          'chat_id' => $_User->getIdTelegram(),
          'text' => $final_message_to_send,
          'parse_mode' => 'html',
          'disable_notifications' => $silent_notify
        ]);
  
        echo $final_message_to_send." Inviato a => ".$_User->getIdTelegram()."\n\n";
      }

    }

    $_User->processTimeleftNotify();    

  }
  
} catch(Exception $e) {
  echo $e->getMessage();
}

