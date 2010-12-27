<?php

/**
 * »º´æÀà
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

require_once('Cache_Abstract.php');
require_once('File.php');
require_once('Memcached.php');

# consistent hashing
require_once('Hash.php');


class CacheException extends Exception {}

final class Cache
{

    /**
     * »º´æ´¦ÀíÀà
     *
     * @var Cache_Abstract
     */
    private $_storage = null;

    /**
     * @return Cache
     */
    static public function initCache($cacheClass = 'HQ_Cache_File')
    {
        return new self($cacheClass);
    }

    private function __construct($cacheClass) {
        $this->_storage = new $cacheClass();
    }

    /**
     * @return hash
     */
    public public function setNode(array $node)
    {
        $hash = new Flexihash();
        $hash->addTargets($node);

        $this->_storage->_hash = $hash;
    }

    /**
     * ÉèÖÃ»º´æÅäÖÃ²ÎÊıĞÅÏ¢
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        $this->_storage->setOption($options);
    }

    /**
     * Ğ´Èë»º´æ
     *
     * @param string $key
     * @param mixed $value
     */
    public function write($key, $value) {
        $this->_storage->write($key, $value);
    }

    /**
     * ¶ÁÈ¡»º´æ
     *
     * @param  string   $key
     * @return mixed|false
     */
    public function read($key)
    {
        return $this->_storage->read($key);
    }

    /**
     * É¾³ı»º´æ
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key)
    {
        return $this->_storage->_delete($key);
    }

    /**
     * Çå¿Õ»º´æ
     *
     * @return boolean
     */
    public function _empty()
    {
        return $this->_storage->_empty();
    }

}


# usage:

$key = 'test';
$value = array('ºÇºÇ', 'ºäºä');


# file cache
$cache = Cache::initCache();    # ³õÊ¼»¯»º´æÀà£¬Ä¬ÈÏÊÇHQ_Cache_File£¬Ò²¿ÉÒÔÊÇHQ_Cache_Memcached

$cache->setNode(range(1,100));  # ÉèÖÃ×Ó½ÚµãÊı×é
#$cache->setOption(array('activeTime'=>3600, 'cacheDir'=>'MyCache'));   # ÉèÖÃÄ¬ÈÏ²ÎÊı

$cache->write($key, $value);    # Ğ´»º´æ
$data = $cache->read($key);     # ¶Á»º´æ
print_r($data);

#$cache->_delete($key); # É¾³ı»º´æ
#$cache->_empty();      # Çå¿Õ»º´æ


/*
# memcached
$cache = Cache::initCache('HQ_Cache_Memcached');

$cache->setNode(
    array(
        // IP:PORT:PERSISTANT
        '127.0.0.1:11211:false',
        '127.0.0.1:11212:false',
        '127.0.0.1:11213:false'
    )
);

$cache->write($key, $value);
$data = $cache->read($key);
$cache->_delete($key);
print_r($data);

*/