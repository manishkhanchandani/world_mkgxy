<?php
class Models_Messages extends App_base
{
  public function message_details($message_id, $uid)
  {
      $sql = sprintf("SELECT * FROM help_messages left join google_auth ON help_messages.uid = google_auth.uid WHERE help_messages.message_id = %s AND help_messages.to_uid = %s", $this->qstr($message_id), $this->qstr($uid));
      $result = $this->_connMain->Execute($sql);
      if (empty($result) || $result->EOF) throw new Exception ("No Message Found.");
      if ($result->fields['message_read'] == 0) {
          $data = array();
          $data['message_id'] = $message_id;
          $data['message_read'] = 1;
          $data['read_date'] = date('Y-m-d H:i:s');
          $where = "message_id = ".$this->qstr($message_id);
          $updateSQL = $this->_connMain->AutoExecute('help_messages', $data, 'UPDATE', $where);
      }
      return $result->fields;
  }

  public function message_view($uid, $nrows=10, $offset=0)
  {
      $sql = sprintf("SELECT * FROM help_messages left join google_auth ON help_messages.uid = google_auth.uid WHERE help_messages.to_uid = %s", $this->qstr($uid));
      $result = $this->_connMain->CacheSelectLimit(self::CACHESECS_MESSAGES, $sql, $nrows, $offset);
      if (empty($result) || $result->EOF) throw new Exception ("No Message Found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       return $return;
  }

    public function add($data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      echo $Models_Googleauth->getUser($data['uid']);
      exit;
      $insertSQL = $this->_connMain->AutoExecute('help_messages', $data, 'INSERT');
      $message_id = $this->_connMain->Insert_ID();
      return $message_id;
    }
}