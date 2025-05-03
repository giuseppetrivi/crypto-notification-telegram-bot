<?php


class User extends Entity {

  protected $id_telegram;
  protected $active;
  protected $expiration_date;
  protected $minutes_interval;
  protected $timeleft_notify;
  protected $lastaction_datetime;
  protected ProcessHandler $_ProcessHandler;
  protected $silent_notify;
  protected NotificationHandler $_NotificationsHandler;

  /**
   * 
   */
  public function __construct($id_telegram) {
    $result = DB::queryFirstRow("SELECT * FROM cryn_users WHERE user_idtelegram=%i", $id_telegram);

    if (!empty($result)) {
      $this->setIdTelegram($result['user_idtelegram']);
      $this->setActive($result['user_active']);
      $this->setExpirationDate($result['user_expirationdate']);
      $this->setMinutesInterval($result['user_minutesinterval']);
      $this->setTimeleftNotify($result['user_timeleft_notify']);
      $this->setLastactionDatetime($result['user_lastaction_datetime']);
      $this->setProcessHandler(new ProcessHandler($this->getIdTelegram(), $result['user_processname']));
      $this->setSilentNotify($result['user_silent_notifies']);
      $this->setNotificationsHandler(new NotificationHandler($this->getIdTelegram()));
    }
    else {
      throw new UserNotRegisteredException("You are not permitted to use this bot");
    }
  }


  /**
   * Static functon to register a new user to the bot
   */
  public static function registerUserToBot($id_telegram) {
    $rows_inserted = DB::query("INSERT INTO cryn_users(user_idtelegram)
      VALUES (%i)", $id_telegram);
    
    if ($rows_inserted==1) {
      return new User($id_telegram);
    }
    throw new Exception("Qualcosa è andato storto nella registrazione dell'utente");
  }


  /**
   * Verifies the validity of the user, based on active and
   * expiration date of eventual contract
   */
  public function checkUserValidity() {
    $active = $this->getActive();
    $expiration_date = $this->getExpirationDate();
    if ($active && ($expiration_date==null || $expiration_date>=date('Y-m-d'))) {
      return true;
    }
    throw new Exception("You can't use this bot anymore");
  }


  /**
   * Change the minutes interval
   */
  public function updateMinutesInterval($new_minutes_interval) {
    $affected_rows = DB::update("cryn_users", 
      ["user_minutesinterval" => $new_minutes_interval],
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setMinutesInterval($new_minutes_interval);

    if ($affected_rows<=1) {
      return true;
    }
    throw new Exception("Errore nell'update dell'intervallo di minuti");
  }

  /**
   * Change the timeleft notify attribute (when the minutes 
   * interval changes, for example)
   */
  public function updateTimeleftNotify() {
    $new_timeleft_notify = ($this->getMinutesInterval() - 5);
    if ($new_timeleft_notify<0) {
      $new_timeleft_notify = 0;
    }

    $affected_rows = DB::update("cryn_users", 
      ["user_timeleft_notify" => $new_timeleft_notify],
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setTimeleftNotify($new_timeleft_notify);

    if ($affected_rows<=1) {
      return true;
    }
    throw new Exception("Errore nell'update dell'intervallo di minuti mancanti");
  }

  /**
   * Changes the timeleft notify attribute automatically,
   * when the cronjob is executed. It also make the reset to
   * the starting value when the attribute is less than 0
   */
  public function processTimeleftNotify() {
    $new_timeleft_notify = ($this->getTimeleftNotify() - 5);
    if ($new_timeleft_notify<0) {
      $new_timeleft_notify = ($this->getMinutesInterval() - 5);
      if ($new_timeleft_notify<0) {
        $new_timeleft_notify = 0;
      }
    }
    $affected_rows = DB::update("cryn_users", 
      ["user_timeleft_notify" => $new_timeleft_notify],
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setTimeleftNotify($new_timeleft_notify);

    if ($affected_rows<=1) {
      return true;
    }
    throw new Exception("Errore nell'update dell'intervallo di minuti mancanti");
  }

  /**
   * Change the value of boolean silent notify attribute
   */
  public function updateSilentNotify() {
    $new_silent_notifies = $this->getSilentNotify() ? 0 : 1;
    $affected_rows = DB::update("cryn_users", 
      ["user_silent_notifies" => $new_silent_notifies],
      ["user_idtelegram" => $this->getIdTelegram()]
    );
    $this->setSilentNotify($new_silent_notifies);

    if ($affected_rows==1) {
      return true;
    }
    throw new Exception("Errore nell'update dello status delle notifiche silenziose");
  }


}