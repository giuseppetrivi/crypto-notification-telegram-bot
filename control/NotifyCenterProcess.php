<?php

/**
 * 
 */
class NotifyCenterProcess extends AbstractProcess {

  protected array $valid_inputs = [
    MenuOptions::BACK => "backProcedure",
    "changeSilentNotifications" => "changeSilentNotificationProcedure"
  ];


  protected function preConditionInput() {
    try {
      parent::preConditionInput();
    } catch (Exception $e) {
      $input = $this->input_from_chat;
      $procedure_name = substr($input, 0, 18);
      $crypto_id = substr($input, 18);
      if ($procedure_name=="changeNotifyStatus" && ($crypto_id!="" && strlen($crypto_id)<5)) {
        $this->function_to_call = "changeNotifyStatusProcedure";
        $this->input_from_chat = $crypto_id;
        return true;
      }
      throw new Exception("L'input non coincide con nessuno degli input possibili");
    }
  }

  protected function mainCode() {
    parent::mainCode();
  }


  /**
   * 
   */
  protected function changeSilentNotificationProcedure() {
    $this->_User->updateSilentNotify();

    $silent = $this->_User->getSilentNotify();
    $raw_notify_status_data = $this->_User->getNotificationsHandler()->getAllNotifyStatus();
    $reply_markup = InlineKeyboards::getNotifyStatus($raw_notify_status_data, $silent);
    $this->_Bot->editMessageReplyMarkup([
      'message_id' => $this->_Bot->getWebhookUpdate()->getMessage()->getMessageId(),
      'reply_markup' => $reply_markup
    ]);

    $this->next_process = 'NotifyCenter';
  }

  /**
   * 
   */
  protected function changeNotifyStatusProcedure() {
    $crypto_id = $this->input_from_chat;
    $this->_User->getNotificationsHandler()->updateNotifyStatusFor($crypto_id);

    $silent = $this->_User->getSilentNotify();
    $raw_notify_status_data = $this->_User->getNotificationsHandler()->getAllNotifyStatus();
    $reply_markup = InlineKeyboards::getNotifyStatus($raw_notify_status_data, $silent);
    $this->_Bot->editMessageReplyMarkup([
      'message_id' => $this->_Bot->getWebhookUpdate()->getMessage()->getMessageId(),
      'reply_markup' => $reply_markup
    ]);

    $this->next_process = 'NotifyCenter';
  }

  /**
   * 
   */
  protected function backProcedure() {
    $reply_markup = Keyboards::getMainMenu();
    $this->_Bot->sendMessage([
      'text' => CustomMessages::mainMenu(),
      'reply_markup' => $reply_markup
    ]);

    $this->next_process = null;
  }

}