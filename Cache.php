<?php

/**
 * »º´æÀà
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

define('BASEDIR', dirname(__FILE__));
define('DIR_SEP', DIRECTORY_SEPARATOR);

require_once(BASEDIR.DIR_SEP.'Cache_Abstract.php');
require_once(BASEDIR.DIR_SEP.'File.php');
require_once(BASEDIR.DIR_SEP.'Memcached.php');

# consistent hashing
require_once(BASEDIR.DIR_SEP.'Hash.php');


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
    public function setNode(array $node)
    {
        $hash = new Flexihash();
        $hash->addTargets($node);

        $this->_storage->_hash = $hash;
    }

    /**
     * ÉèÖÃ»º´æÅäÖÃ²ÎÊýÐÅÏ¢
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        $this->_storage->setOption($options);
    }

    /**
     * Ð´Èë»º´æ
     *
     * @param string $key
     * @param mixed $value
     * @param int  $time
     */
    public function write($key, $value, $time = null) {
        $this->_storage->write($key, $value, $time);
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
     * É¾³ý»º´æ
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
