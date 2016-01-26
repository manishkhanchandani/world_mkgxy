<?php
//http://world.mkgalaxy.com/api/help/websites/add?data[uid]=1&data[to_uid]=2&data[message]=hello
class App_websites_add extends App_base
{
    public function execute()
    {
      $request = $_REQUEST;
      $data = $request['data'];
      if (empty($data)) {
        throw new Exception('Missing Data');
      }
      if (empty($data['uid'])) {
        throw new Exception('Missing Uid');
      }
      $Models_Websites = new Models_Websites();
      $website_id = $Models_Websites->add($data);
      $this->return = array('confirm' => 'New website created successfully', 'website_id' => $website_id);
    }
}