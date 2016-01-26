<?php
//http://world.mkgalaxy.com/api/help/messages/view?uid=1
class App_messages_view extends App_base
{
    public function execute()
    {
      if (empty($_GET['uid'])) {
        throw new Exception('Enter uid');
      }
      $nrows = 10;
      $offset = 0;
      if (empty($_GET['offset'])) {
          $offset = $_GET['offset'];
      }
      $uid = $_GET['uid'];
      $Models_Messages = new Models_Messages();
      $return = $Models_Messages->message_view($uid, $nrows, $offset);
      $this->return = $return;
    }
}