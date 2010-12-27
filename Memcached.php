<?php

/**
 * Memcached������
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
     * �������ò���
     *
     * @var array
     */
    protected $_options = array(
       'activeTime' => 86400,    # ������������(��)��Ĭ��1��
       'compress' => false, # �Ƿ�ѹ��
       'compress_threshold' => 102400,   # ���������ֽڵ�����ʱ�����Զ�ѹ��������compress����Ч
    );

    protected $_server = array(
       'host' => '127.0.0.1',   # memcache��������ַ
       'port' => 11211,  # �˿�
       'persistant' => false    # �Ƿ�ʹ�ó־�����
    );

    /**
     * ���û������ò�����Ϣ
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
     * ��ȡ��������
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
     * д�뻺������
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
     * ɾ������
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
     * ��ջ���
     *
     * @return boolean
     */
    public function _empty()
    {
        #return $this->_memcache->flush();
    }

    /**
     * ���ӻ��������
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
     * �Ͽ����������
     *
     * @return string
     */
    protected function _disconnect()
    {
        return $this->_memcache->close();
    }

}
