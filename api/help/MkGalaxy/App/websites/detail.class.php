<?php
//http://world.mkgalaxy.com/api/help/websites/detail?id=1&cache=0
class App_websites_detail extends App_base
{
    public function execute()
    {
      if (empty($_GET['id'])) {
        throw new Exception('missing id');
      }
      $cache = 1;
      if (isset($_GET['cache'])) {
        $cache = $_GET['cache'];
      }
      $Models_Websites = new Models_Websites();
      $return = $Models_Websites->detail($_GET['id'], $cache);
      $this->return = $return;
    }
}