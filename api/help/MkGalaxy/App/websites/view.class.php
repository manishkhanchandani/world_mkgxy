<?php
//http://world.mkgalaxy.com/api/help/websites/view?uid=1
class App_websites_view extends App_base
{
    public function execute()
    {
      if (empty($_GET['uid'])) {
        throw new Exception('Enter uid');
      }
      $nrows = 10000;
      $offset = 0;
      if (empty($_GET['offset'])) {
          $offset = $_GET['offset'];
      }
      $cache = 1;
      if (isset($_GET['cache'])) {
        $cache = $_GET['cache'];
      }
      $uid = $_GET['uid'];
      $Models_Websites = new Models_Websites();
      $return = $Models_Websites->view($uid, $nrows, $offset, $cache);
      $this->return = $return;
    }
}