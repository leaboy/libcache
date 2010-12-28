<?php

/**
 * 缓存类
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
     * 缓存处理类
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
     * 设置缓存配置参数信息
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        $this->_storage->setOption($options);
    }

    /**
     * 写入缓存
     *
     * @param string $key
     * @param mixed $value
     */
    public function write($key, $value) {
        $this->_storage->write($key, $value);
    }

    /**
     * 读取缓存
     *
     * @param  string   $key
     * @return mixed|false
     */
    public function read($key)
    {
        return $this->_storage->read($key);
    }

    /**
     * 删除缓存
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key)
    {
        return $this->_storage->_delete($key);
    }

    /**
     * 清空缓存
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
$value = array('libCache', '压缩');


# file cache
$cache = Cache::initCache();    # 初始化缓存类，默认是HQ_Cache_File，也可以是HQ_Cache_Memcached

$cache->setNode(range(1,100));  # 设置子节点数组
#$cache->setOption(array('compress'=>true));    # 数据压缩
#$cache->setOption(array('activeTime'=>3600, 'cacheDir'=>'MyCache'));   # 设置默认参数

$cache->write($key, $value);    # 写缓存
$data = $cache->read($key);     # 读缓存
print_r($data);

#$cache->_delete($key); # 删除缓存
#$cache->_empty();      # 清空缓存


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