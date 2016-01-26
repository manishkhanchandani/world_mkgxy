<?php
class Models_Googleauth extends App_base
{
    public function getUser($uid, $useCache=true)
    {
      $sql = sprintf("SELECT * FROM google_auth WHERE google_auth.uid = %s", $this->qstr($uid));
      if ($useCache)
          $result = $this->_connMain->CacheExecute(self::CACHESECS_GOOGLEAUTH_USER, $sql);
      else
          $result = $this->_connMain->Execute($sql);
      $return = $result->fields;
      if (empty($result) || $result->EOF || empty($return)) throw new Exception ("No User Found.");
       return $return;
    }
}