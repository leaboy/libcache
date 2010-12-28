<?php

/**
 * 文件缓存类
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

class HQ_Cache_File extends HQ_Cache_Abstract
{
    public $_hash = null;

    /**
     * 缓存配置参数
     *
     * @var array
     */
    protected $_options = array(
       'activeTime' => 86400,   # 缓存生存周期(秒)，默认1天
       'cacheDir' => 'cache',   # 缓存文件目录
       'cacheDirMode' => 0777,  # 缓存目录权限
       'compress' => false  # 是否压缩
    );

    /**
     * 设置缓存配置参数信息
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        parent::setOption($options);
    }

    /**
     * 读取缓存数据
     *
     * @param  string   $key
     * @return mixed|false
     */
    public function read($key)
    {
        if (!$this->checkPath($this->_options['cacheDir']))
            return false;

        $cacheFile = $this->getFile($key);
        if (!is_readable($cacheFile) || !$cacheData = @file_get_contents($cacheFile))
            return false;

        $this->_options['compress'] && $cacheData = @gzinflate($cacheData);
        $cacheData = unserialize($cacheData);

        if ($cacheData['activeTime'] < time())
        {
            $this->_delete($key);
            return false;
        }

        return $cacheData['data'];
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
        if (!$this->checkPath($this->_options['cacheDir']))
            return false;

        $cacheData = array('activeTime' => time()+$this->_options['activeTime'], 'data' => $value);
        $cacheData = serialize($cacheData);

        $this->_options['compress'] && $cacheData = gzdeflate($cacheData);

        return file_put_contents($this->getFile($key), $cacheData, LOCK_EX);
    }

    /**
     * 删除缓存
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key)
    {
        return @unlink($this->getFile($key));
    }

    /**
     * 清空缓存
     *
     * @return boolean
     */
    public function _empty($cacheDir = null)
    {
        $cacheDir || $cacheDir = $this->_options['cacheDir'];
        if (!@opendir($cacheDir))
            return false;

        $files = scandir($cacheDir);
        foreach ($files as $file)
        {
            $item = $cacheDir . DIR_SEP . $file;

            if (is_dir($item) && $file != '.' && $file != '..')
            {
                $this->_empty($item);
                @rmdir($item);
            }
            else
            {
                @unlink($item);
            }
        }
        return true;
    }

    /**
     * 生成缓存文件名
     *
     * @return string
     */
    protected function getFile($key)
    {
        $this->_hash && $subDir = $this->_hash->lookup($key);
        $subDir && $subDir = DIR_SEP . md5($subDir);
        $cachePath = $this->_options['cacheDir'] . $subDir;

        if (!$this->checkPath($cachePath))
            return false;

        return $cachePath . DIR_SEP . md5($key);
    }

    /**
     * 检查路径
     *
     * @return boolean
     */
    protected function checkPath($dir)
    {
        if (is_dir($dir))
            return true;

        if (!@mkdir($dir, $this->_options['cacheDirMode']))
            return false;
        else
            @chmod($dir, $this->_options['cacheDirMode']);

        return true;
    }

}
