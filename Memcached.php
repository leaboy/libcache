<?php

/**
 * Memcached缓存类
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

class HQ_Cache_Memcached extends HQ_Cache_Abstract
{
    public $_hash = null;

    protected $_memcache = null;

    /**
     * 缓存配置参数
     *
     * @var array
     */
    protected $_options = array(
       'activeTime' => 86400,    # 缓存生存周期(秒)，默认1天
       'compress' => false, # 是否压缩
       'compress_threshold' => 102400,   # 超过多少字节的数据时进行自动压缩，开启compress才有效
    );

    protected $_server = array(
       'host' => '127.0.0.1',   # memcache服务器地址
       'port' => 11211,  # 端口
       'persistant' => false    # 是否使用持久链接
    );

    /**
     * 设置缓存配置参数信息
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        parent::setOption($options);

        $this->_memcache = new Memcache();
        $this->_options['compress'] && $this->_memcache->setcompressthreshold($this->_options['compress_threshold']);
    }

    /**
     * 读取缓存数据
     *
     * @param  string   $key
     * @return mixed|false
     */
    public function read($key)
    {
        $key = $this->_connect($key);
        $result = $this->_memcache->get($key);
        $this->_disconnect();

        return $result;
    }

    /**
     * 写入缓存数据
     *
     * @param  string   $key
     * @param  mixed  $value
     * @return boolean
     */
    public function write($key, $value)
    {
        $key = $this->_connect($key);
        $result = $this->_memcache->set($key, $value, $this->_options['compress'], $this->_options['activeTime']);
        $this->_disconnect();

        return $result;
    }

    /**
     * 删除缓存
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key)
    {
        $key = $this->_connect($key);
        $result = $this->_memcache->delete($key);
        $this->_disconnect();

        return $result;
    }

    /**
     * 清空缓存
     *
     * @return boolean
     */
    public function _empty()
    {
        #return $this->_memcache->flush();
    }

    /**
     * 连接缓存服务器
     *
     * @return string
     */
    protected function _connect($key)
    {
        $key = md5($key);

        $server = $this->_hash ? $this->_hash->lookup($key) : $this->_server;
        is_array($server) || $server = explode(':', $server);
        list($ip, $port, $mode) = $server;
        @$conn = $mode ? $this->_memcache->pconnect($ip, $port) : $this->_memcache->connect($ip, $port);
        unset($server, $ip, $port, $mode);

        if (!$conn)
            return false;

        return $key;
    }

    /**
     * 断开缓存服务器
     *
     * @return string
     */
    protected function _disconnect()
    {
        return $this->_memcache->close();
    }

}
