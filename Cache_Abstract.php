<?php

/**
 * ���������
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

abstract class Cache_Abstract
{

    public function __construct(array $options = array())
    {
        $this->setOption($options);
    }

    /**
     * ���û������ò�����Ϣ
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        foreach ($options as $key => $value)
            if (isset($this->_options[$key]))
                $this->_options[$key] = $value;
    }

    /**
     * ��ȡ��������
     *
     * @param  string   $key
     * @return mixed|false
     */
    abstract public function read($key);

    /**
     * д�뻺������
     *
     * @param  string   $key
     * @param  mixed  $value
     * @param  int  $time
     * @return boolean
     */
    abstract public function write($key, $value, $time = null);

    /**
     * ɾ������
     *
     * @param  string   $key
     * @return boolean
     */
    abstract public function _delete($key);

    /**
     * ��ջ���
     *
     * @return boolean
     */
    abstract public function _empty();
}