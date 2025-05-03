<?php


/**
 * Class to handle process in database
 */
class ProcessHandler extends Entity {

  protected $id_telegram;
  protected $process_name;

  public function __construct($id_telegram, $process_name) {
    $this->setIdTelegram($id_telegram);
    $this->setProcessName($process_name);
  }

  /**
   * 
   */
  public function getProcessClassName() {
    $process_name = $this->getProcessName();
    if ($process_name == null) {
      $process_name = "Main";
    }
    return $process_name . "Process";
  }

  /**
   * 
   */
  public function updateProcess($new_process_name) {
    $affected_rows = DB::update("cryn_users", 
      ["user_processname" => $new_process_name], 
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setProcessName($new_process_name);
  }

  /**
   * 
   */
  public function updateProcessData($new_process_data) {
    $affected_rows = DB::update("cryn_users", 
      ["user_processdata" => $new_process_data], 
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setProcessData($new_process_data);
  }

  /**
   * 
   */
  public function setProcessToNull() {
    $affected_rows = DB::update("cryn_users",
      ["user_processname" => null],
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setProcessName(null);
  }

}