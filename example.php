<?php

require_once('Cache.php');

# usage:

$key = 'test';
$value = array('libCache', 'ѹ��');


# file cache
$cache = Cache::initCache();    # ��ʼ�������࣬Ĭ����File��Ҳ������Memcached

$cache->setNode(range(1,100));  # �����ӽڵ�����
#$cache->setOption(array('compress'=>true));    # ����ѹ��
#$cache->setOption(array('activeTime'=>3600, 'cacheDir'=>'MyCache'));   # ����Ĭ�ϲ���

#$cache->write($key, $value);    # д����
$cache->write($key, $value, -1);    # д����,������
$data = $cache->read($key);     # ������
print_r($data);

#$cache->_delete($key); # ɾ������
#$cache->_empty();      # ��ջ���


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