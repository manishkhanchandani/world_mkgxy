<?php
class Models_Websites extends App_base
{

    public function add($data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($data['uid'], false);
      $insertSQL = $this->_connMain->AutoExecute('websites', $data, 'INSERT');
      $message_id = $this->_connMain->Insert_ID();
      return $message_id;
    }

    public function edit($id, $data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($data['uid'], false);
      $where = 'id = '.$this->qstr($id);
      $insertSQL = $this->_connMain->AutoExecute('websites', $data, 'UPDATE', $where);
      return $id;
    }

    public function delete($uid, $website_id)
    {
      $sql = sprintf("delete FROM websites WHERE uid = %s and website_id=%s", $this->qstr($uid), $this->qstr($website_id));
      $result = $this->_connMain->Execute($sql);
      return true;
    }

    public function view($uid, $nrows=10, $offset=0, $cache=true)
    {
        $sql = sprintf("SELECT * FROM websites left join google_auth ON websites.uid = google_auth.uid WHERE websites.uid = %s", $this->qstr($uid));
        if (!empty($cache)) {
            $result = $this->_connMain->CacheSelectLimit(60, $sql, $nrows, $offset);
        } else {
            $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
        }
        if (empty($result) || $result->EOF) throw new Exception ("No Website Found.");
        $return = array();
        while (!$result->EOF) {
            $return[] = $result->fields;
            $result->MoveNext();
         }
         return $return;
    }

    public function detail($id, $cache=false)
    {
        $sql = sprintf("SELECT * FROM websites left join google_auth ON websites.uid = google_auth.uid WHERE websites.id = %s", $this->qstr($id));
        if ($cache) {
            $result = $this->_connMain->CacheExecute(300, $sql);
        } else {
            $result = $this->_connMain->Execute($sql);
        }

        if (empty($result) || $result->EOF) throw new Exception ("No Website Found.");
        return $result->fields;
    }

}