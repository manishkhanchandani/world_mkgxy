<?php
class App_base
{
    protected $_connMain;

    public $return = array();

    const CACHESECS_MESSAGES = 60;

    const CACHESECS_MESSAGESDETAIL = 1000;

    const CACHESECS_GOOGLEAUTH_USER = 7200;

    public function __construct()
    {
        global $connMainAdodb;
        $this->_connMain = $connMainAdodb;
        //$this->_connMain->debug = true;
    }

    protected function qstr($value)
    {
        return $this->_connMain->qstr($value);
    }
}