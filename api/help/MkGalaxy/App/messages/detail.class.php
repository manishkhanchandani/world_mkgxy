<?php
//http://world.mkgalaxy.com/api/help/messages/detail?message_id=1&uid=108014758311611089087
class App_messages_detail extends App_base
{
    public function execute()
    {
      if (empty($_GET['uid'])) {
        throw new Exception('Enter uid');
      }
      if (empty($_GET['message_id'])) {
        throw new Exception('Enter message id');
      }
      $uid = $_GET['uid'];
      $message_id = $_GET['message_id'];
      $Models_Messages = new Models_Messages();
      $return = $Models_Messages->message_details($message_id, $uid);
      $this->return = $return;
    }
}