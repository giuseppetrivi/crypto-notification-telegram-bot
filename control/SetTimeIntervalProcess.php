<?php

class SetTimeIntervalProcess extends AbstractProcess {

  protected array $valid_inputs = [
    MenuOptions::BACK => "backProcedure"
  ];

  protected function preConditionInput() {
    try {
      parent::preConditionInput();
    } catch (Exception $e) {
      $input = $this->input_from_chat;
      if ($input>=5 && $input<=1440 && $input%5==0) {
        $this->function_to_call = "changeMinutesIntervalProcedure";
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
  protected function changeMinutesIntervalProcedure() {
    $this->_User->updateMinutesInterval($this->input_from_chat);
    $this->_User->updateTimeleftNotify();

    $this->_Bot->sendMessage([
      'text' => CustomMessages::newMinutesIntervalInformations($this->input_from_chat)
    ]);

    $this->backProcedure();
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