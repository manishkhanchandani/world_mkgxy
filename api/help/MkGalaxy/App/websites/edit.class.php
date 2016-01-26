<?php
//http://world.mkgalaxy.com/api/help/websites/edit?id=2&data[uid]=112913147917981568678&data[content]=hello
class App_websites_edit extends App_base
{
    public function execute()
    {
      $request = $_REQUEST;
      $data = $request['data'];
      if (empty($_GET['id'])) {
        throw new Exception('Missing ID');
      }
      if (empty($data)) {
        throw new Exception('Missing Data');
      }
      if (empty($data['uid'])) {
        throw new Exception('Missing Uid');
      }
      $Models_Websites = new Models_Websites();
      $Models_Websites->edit($_GET['id'], $data);
      $this->return = array('confirm' => 'Website updated successfully', 'website_id' => $_GET['id']);
    }
}