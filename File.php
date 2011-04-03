<?php

/**
 * �ļ�������
 *
 * @author     leaboy.w <leaboy.w@gmail.com>
 * @copyright  Copyright (c) 2010 (http://wiki.nocn.net)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 */

class File extends Cache_Abstract
{
    public $_hash = null;

    /**
     * �������ò���
     *
     * @var array
     */
    protected $_options = array(
       'activeTime' => 86400,   # ������������(��)��Ĭ��1��
       'cacheDir' => 'cache',   # �����ļ�Ŀ¼
       'cacheDirMode' => 0777,  # ����Ŀ¼Ȩ��
       'compress' => false  # �Ƿ�ѹ��
    );

    /**
     * ���û������ò�����Ϣ
     *
     * @param  array  $options
     */
    public function setOption(array $options)
    {
        parent::setOption($options);
    }

    /**
     * ��ȡ��������
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

        if ($cacheData['activeTime'] > 0 && $cacheData['activeTime'] < time())
        {
            $this->_delete($key);
            return false;
        }

        return $cacheData['data'];
    }

    /**
     * д�뻺������
     *
     * @param  string   $key
     * @param  mixed  $value
     * @param  int  $time
     * @return boolean
     */
    public function write($key, $value, $time = null)
    {
        if (!$this->checkPath($this->_options['cacheDir']))
            return false;

        if (!$time == -1) {
            $time = intval($time);
            $time = $time > 0 ? time() + $time : time() + $this->_options['activeTime'];
        }

        $cacheData = array('activeTime' => $time, 'data' => $value);
        $cacheData = serialize($cacheData);

        $this->_options['compress'] && $cacheData = gzdeflate($cacheData);

        return file_put_contents($this->getFile($key), $cacheData, LOCK_EX);
    }

    /**
     * ɾ������
     *
     * @param  string   $key
     * @return boolean
     */
    public function _delete($key)
    {
        return @unlink($this->getFile($key));
    }

    /**
     * ��ջ���
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
     * ���ɻ����ļ���
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
     * ���·��
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
