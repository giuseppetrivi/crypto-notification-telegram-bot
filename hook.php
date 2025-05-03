<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/business_core_autoloader.php';


/* Object for the base configuration infos */
$_SystemConfig = ConfigurationInfo::setInstance(true);

DB::$user = $_SystemConfig->getDbUsername();
DB::$password = $_SystemConfig->getDbPassword();
DB::$dbName = $_SystemConfig->getDbName();
//DB::$host = $_SystemConfig->getDbHost();


/* Starting bot and check to base objects */
$telegram_bot_api_token = $_SystemConfig->getTelegramBotApiToken();
$_Bot = new BotCustom($telegram_bot_api_token);

$_Update = $_Bot->getWebhookUpdate();
$_Chat = $_Update->getChat();
if ($_Chat==null) {
  throw new Error("Chat is null");
  exit;
}
$chat_id = $_Chat->getId();
$input_from_chat = null;

$_Message = $_Update->getMessage();
$_CallbackQuery = $_Update->getCallbackQuery();
if ($_CallbackQuery!=null) {
  $input_from_chat = $_CallbackQuery->getData();
}
else if ($_Message!=null) {
  $input_from_chat = $_Message->getText();
}
else {
  throw new Error("Message and CallbackQuery are null");
  exit;
}


/* Set the default value of chat_id */
$_Bot::setChatId($chat_id);


/* Checking user permissions and existance */
$_User = null;
try {
  $_User = new User($chat_id);
  $_User->checkUserValidity();
} catch (UserNotRegisteredException $e) {

  try {
    if ($_SystemConfig->getOpenAccessToBot()) {
      $_User = User::registerUserToBot($chat_id);
    }
    else {
      throw $e;
    }
  } catch (UserNotRegisteredException $e) {
    $_Bot->sendMessage([
      'text' => $e->getMessage()
    ]);
    exit;
  }

} catch (UserNotRegisteredException $e) {
  $_Bot->sendMessage([
    'text' => $e->getMessage()
  ]);
  exit;
}


/* Handle Restart command to forcing restart of the bot */
try {

  if ($input_from_chat==MenuOptions::COMMAND_RESTART) {
    $_Process = new RestartProcess($input_from_chat, $_Bot, $_User);
    $_Process->codeToRun();
    exit;
  }

} catch (Exception $e) {
  $_Bot->sendMessage([
    'text' => "Error: " . $e->getMessage() . " "
  ]);
  exit;
}


/* Starting processess */
$process_classname = $_User->getProcessHandler()->getProcessClassName();
$_Process = new $process_classname($input_from_chat, $_Bot, $_User);
try {
  $_Process->codeToRun();
}
catch (Exception $e) {
  $_Bot->sendMessage([
    'text' => "Error: " . $e->getMessage() . " "
  ]);
  exit;
}
