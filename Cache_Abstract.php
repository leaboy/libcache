<?php

/**
 * 缓存抽象类
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
     * 设置缓存配置参数信息
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
     * 读取缓存数据
     *
     * @param  string   $key
     * @return mixed|false
     */
    public function read($key) {}

    /**
     * 写入缓存数据
     *
     * @param  string   $key
     * @param  mixed  $value
     * @return boolean
     */
    public function write($key, $value) {}

    /**
     * 删除缓存
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key) {}

    /**
     * 清空缓存
     *
     * @return boolean
     */
    public function _empty() {}
}