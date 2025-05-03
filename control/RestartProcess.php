<?php


/**
 * Process to restart bot (special cases)
 */
class RestartProcess extends AbstractProcess {

  protected array $valid_inputs = [
    MenuOptions::COMMAND_RESTART => 'restartProcedure'
  ];

  protected function mainCode() {
    parent::mainCode();
  }


  /**
   * 
   */
  protected function restartProcedure() {
    $this->_User->getProcessHandler()->setProcessToNull();
    $reply_markup = Keyboards::getMainMenu();

    $this->_Bot->sendMessage([
      'text' => CustomMessages::welcomeStart($this->_Bot->getWebhookUpdate()->getMessage()->getFrom()->getUsername()),
      'reply_markup' => $reply_markup
    ]);
  }
  

}