<?php

/**
 * Class to handle the N to N notifications table
 */
class NotificationHandler extends Entity {

  protected $id_telegram;

  public function __construct($id_telegram) {
    $this->setIdTelegram($id_telegram);
  }

  /**
   * Takes all notification records useful to send automatic messages
   */
  public static function getAllNotificationsSettings() {
    $result = DB::query("SELECT *
      FROM cryn_notifications as n 
      INNER JOIN cryn_users as u ON n.user_idtelegram=u.user_idtelegram
      INNER JOIN cryn_cryptocurrencies as c ON c.crypto_id=n.crypto_id
      WHERE n.notify_status=1
      ORDER BY n.user_idtelegram"
    );

    if (!empty($result)) {
      return $result;
    }
    throw new Exception("Errore nella tabella di notifiche");
  }


  /**
   * Get the actual status of notify of a specific crypto
   */
  private function getNotifyStatus($crypto_id) {
    $actual_status = DB::queryFirstRow("SELECT notify_status 
      FROM cryn_notifications
      WHERE user_idtelegram=%i AND crypto_id=%s", 
      $this->getIdTelegram(), 
      $crypto_id);

    if (empty($actual_status)) {
      throw new Exception("Errore nel prendere lo status della crypto in db");
    }
    return $actual_status['notify_status'];
  }


  /**
   * Updates the notify status for a certein crypto
   */
  public function updateNotifyStatusFor($crypto_id) {
    $actual_status = $this->getNotifyStatus($crypto_id);
    $new_status = $actual_status==0 ? 1 : 0;
    $affected_rows = DB::update("cryn_notifications", 
      ["notify_status" => $new_status],
      [
        "user_idtelegram" => $this->getIdTelegram(),
        "crypto_id" => $crypto_id
      ]
    );

    if ($affected_rows!=1) {
      throw new Exception("Errore nell'update dello status della notifica");
    }
    return $new_status;
  }

  /**
   * Takes all notifies infos of the user
   */
  public function getAllNotifyStatus() {
    $raw_data = DB::query("SELECT cn.crypto_id, cc.crypto_name, cn.user_idtelegram, cn.notify_status
      FROM cryn_users as cu
      INNER JOIN cryn_notifications as cn ON cu.user_idtelegram = cn.user_idtelegram
      INNER JOIN cryn_cryptocurrencies as cc ON cn.crypto_id = cc.crypto_id
      WHERE cn.user_idtelegram=%i", $this->getIdTelegram());
    
    if (empty($raw_data)) {
      throw new Exception("Errore nel prendere lo stato di tutte le notifiche dell'utente");
    }
    return $raw_data;
  }


}