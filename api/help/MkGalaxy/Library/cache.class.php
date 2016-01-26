<?php
class Library_cache
{
    protected $cache;
    public function __construct()
    {
        $this->setOptions();
    }

    public function getOptions()
    {
        return $this->cache;
    }

    public function setOptions($lifetime=0)
    {
        $frontendOptions = array(
           'lifetime' => $lifetime, // cache lifetime of unlimited
           'automatic_serialization' => true
        );
         
        $backendOptions = array(
            'cache_dir' => SITE_DIR.'/ADODB_cache/zend' // Directory where to put the cache files
        );
         
        // getting a Zend_Cache_Core object
        $this->cache = Zend_Cache::factory('Core',
                                     'File',
                                     $frontendOptions,
                                     $backendOptions);
    }

    public function setCache($key, $value)
    {
        $this->cache->save($key, $value);
    }

    public function getCache($key)
    {
        $result = $this->cache->load($key);
        return $result;
    }
}