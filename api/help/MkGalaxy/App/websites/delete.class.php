<?php
//http://world.mkgalaxy.com/api/help/websites/add?data[uid]=1&data[to_uid]=2&data[message]=hello
class App_websites_add extends App_base
{
    public function execute()
    {
      if (empty($_GET['uid'])) {
        throw new Exception('Enter uid');
      }
      if (empty($_GET['website_id'])) {
        throw new Exception('Enter website_id');
      }
      $Models_Websites = new Models_Websites();
      $website_id = $Models_Websites->delete($_GET['uid'], $_GET['website_id']);
      $this->return = array('confirm' => 'New website created successfully', 'website_id' => $website_id);
    }
}