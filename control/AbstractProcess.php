<?php

/**
 * Base class to handle processes.
 * 
 * The logic is this:
 * Every process has its pre-conditions, based on the validity of inputs.
 * Inputs can be verified statically od dinamically.
 * 
 * Then there is the core code of the process, that depends on the input
 * 
 * Finally, there is the only post-condition that is the change of the status
 * in the database row for teh process
 */
abstract class AbstractProcess {
  protected $_Bot;
  protected $_User;

  /**
   * Input (message, query message, photo, ...) from the telegram bot
   */
  protected $input_from_chat = '';

  /**
   * Array of all valid inputs possibile for the current specific
   * process
   */
  protected array $valid_inputs = [];

  /**
   * Name of the function to be called by the mainCode method
   */
  protected string|null $function_to_call = null;

  /**
   * Next process to be setted in the database at the end of all
   * the core code of the current process. If null it will delete
   * the record process in the database. This means that the next
   * process will be the MainProcess (start menu)
   */
  protected string|null $next_process = null;
  /**
   * Data to be setted fo the next process. If null, it will be empty
   */
  protected string|null $data_for_next_process = null;


  public function __construct($input_from_chat, $_Bot, $_User) {
    $this->input_from_chat = $input_from_chat;
    $this->_Bot = $_Bot;
    $this->_User = $_User;
  }

  

  /**
   * Validate automatically the inputs basing on the valid_inputs list.
   * This function is protected because it can be override to make the
   * stantard static validation more custom
   */
  protected function validateStaticInputs() {
    if (count($this->valid_inputs)!=0 && array_key_exists($this->input_from_chat, $this->valid_inputs)) {
      $this->function_to_call = $this->valid_inputs[$this->input_from_chat];
      return true;
    }
    throw new Exception("Input non presente tra gli input statici validi per questa procedura");
  }

  /**
   * Change the process into the database to set it to the next
   * process (eventually also with data)
   */
  private function changeProcess() {
    $this->_User->getProcessHandler()->updateProcess($this->next_process);
  }



  /**
   * Verifies the validity of the input of the process, to satify its
   * pre-conditions
   * This has also to set the specific procedure to be executed in the
   * mainCode block
   * 
   * @return true|Exception
   */
  protected function preConditionInput() {
    $this->validateStaticInputs();
  }

  /**
   * Contains the core code to execute the actions of the process.
   * There is the standard call to the function to execute setted
   * by the pre-condition check
   */
  protected function mainCode() {
    call_user_func(array($this, $this->function_to_call));
  }

  /**
   * Verify all the post-conditions, starting from the
   * change of the process
   */
  protected function postConditionProcess() {
    $this->changeProcess();
  }

  /**
   * Function visible from outside the boundaries of Process class
   * that executes the code with pre and post-conditions
   */
  public function codeToRun() {
    $this->preConditionInput();
    $this->mainCode();
    $this->postConditionProcess();
  }


}