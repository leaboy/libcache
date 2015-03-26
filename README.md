# A PHP Caching solutions #

## Example ##
```
require_once('Cache.php');

# usage:

$key = 'test';
$value = array('libCache', '压缩');


## file cache
$cache = Cache::initCache();    # 初始化缓存类，默认是File，也可以是Memcached

$cache->setNode(range(1,100));  # 设置子节点数组
#$cache->setOption(array('compress'=>true));    # 数据压缩
#$cache->setOption(array('activeTime'=>3600, 'cacheDir'=>'MyCache'));   # 设置默认参数

#$cache->write($key, $value);    # 写缓存
$cache->write($key, $value, -1);    # 写缓存,不过期
$data = $cache->read($key);     # 读缓存
print_r($data);

#$cache->_delete($key); # 删除缓存
#$cache->_empty();      # 清空缓存


## memcached
$cache = Cache::initCache('Memcached');

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
```
