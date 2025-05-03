<?php


/**
 * Process at start of the bot
 */
class MainProcess extends AbstractProcess {

  protected array $valid_inputs = [
    MenuOptions::COMMAND_START => 'startProcedure',
    MenuOptions::SET_TIME_INTERVAL => 'openSetIntervalProcedure',
    MenuOptions::NOTIFY_CENTER => 'openNotifyCenterProcedure',
  ];

  protected function mainCode() {
    parent::mainCode();
  }


  /**
   * 
   */
  protected function startProcedure() {
    $reply_markup = Keyboards::getMainMenu();

    $this->_Bot->sendMessage([
      'text' => CustomMessages::welcomeStart($this->_Bot->getWebhookUpdate()->getMessage()->getFrom()->getUsername()),
      'reply_markup' => $reply_markup
    ]);
  }

  /**
   * 
   */
  protected function openSetIntervalProcedure() {
    $reply_markup = Keyboards::getSetTimeInterval();
    $this->_Bot->sendMessage([
      'text' => CustomMessages::intervalInformations($this->_User->getMinutesInterval()),
      'reply_markup' => $reply_markup
    ]);

    $this->next_process = 'SetTimeInterval';
  }

  /**
   * 
   */
  protected function openNotifyCenterProcedure() {
    $raw_notify_status_data = $this->_User->getNotificationsHandler()->getAllNotifyStatus();
    $silent = $this->_User->getSilentNotify();

    $reply_markup = InlineKeyboards::getNotifyStatus($raw_notify_status_data, $silent);
    $this->_Bot->sendMessage([
      'text' => CustomMessages::notificationsInformations(),
      'reply_markup' => $reply_markup
    ]);

    $reply_markup = Keyboards::getOnlyBack();
    $this->_Bot->sendMessage([
      'text' => CustomMessages::PRESS_BACK,
      'reply_markup' => $reply_markup
    ]);

    $this->next_process = 'NotifyCenter';
  }

}