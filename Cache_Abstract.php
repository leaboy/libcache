<?php

/**
 * ���������
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

abstract class HQ_Cache_Abstract
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
    public function read($key) {}

    /**
     * д�뻺������
     *
     * @param  string   $key
     * @param  mixed  $value
     * @return boolean
     */
    public function write($key, $value) {}

    /**
     * ɾ������
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key) {}

    /**
     * ��ջ���
     *
     * @return boolean
     */
    public function _empty() {}
}