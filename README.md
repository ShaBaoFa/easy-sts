# EasySts

---

<p align="center">
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=8.0-brightgreen.svg?maxAge=2592000"></a>
  <a href="https://github.com/swoole/swoole-src"><img src="https://img.shields.io/packagist/dt/wlfpanda1012/aliyun-sts"></a>
</p>

## 介绍

一款简单易用的 临时访问凭证(`StsToken`) 封装包.
写法上借鉴了 `Hyperf` 的 `FileSystem` 包.
使用了 适配器 的方式实现了 不同服务端的 `Sts` 接口.
只需要做简单的配置即可使用.

## 平台支持
- ~~[Local](http://localhost)~~
- [阿里云](https://www.aliyun.com/)
- [天翼云](https://www.ctyun.com/)

## 安装
    
```shell
composer require wlfpanda1012/easy-sts
# 如果 需要用天翼云
composer require wlfpanda1012/ctyun-sts
# 如果 需要用阿里云
composer require wlfpanda1012/aliyun-sts
```
## 单独包传送门
- [wlfpanda1012/ctyun-sts](https://github.com/ShaBaoFa/ctyun-sts) 天翼云STS
- [wlfpanda1012/aliyun-sts](https://github.com/ShaBaoFa/aliyun-sts) 阿里云STS

## 发布配置
    
```shell
php bin/hyperf.php vendor:publish wlfpanda1012/easy-sts
```

## 配置用例

```php
<?php

declare(strict_types=1);

use Wlfpanda1012\EasySts\Adapter\AliyunStsAdapterFactory;
use Wlfpanda1012\EasySts\Adapter\CtyunStsAdapterFactory;
use Wlfpanda1012\EasySts\Adapter\LocalStsAdapterFactory;

use function Hyperf\Support\env;

return [
    // 默认配置
    // 预留了非公网环境的STS使用可能性(未来项目需求,暂未开发)
    // 配置 default 修改为 aliyun 或者 ctyun
    // 配置好对应的环境变量即可
    'default' => 'local',
    'adapter' => [
        'local' => [
            'driver' => LocalStsAdapterFactory::class,
            'accessId' => 'username',
            'accessSecret' => 'password',
        ],
        'aliyun' => [
            'driver' => AliyunStsAdapterFactory::class,
            'accessId' => env('ALIYUN_STS_ACCESS_ID'),
            'accessSecret' => env('ALIYUN_STS_ACCESS_SECRET'),
            'roleArn' => env('ALIYUN_STS_ROLE_ARN', 'acs:ram::xxxxxxxxxxx:role/xxx'),
            'roleSessionName' => env('ALIYUN_STS_ROLE_SESSION_NAME', 'xxxxxxxxxxx'),
            'endpoint' => env('ALIYUN_STS_ENDPOINT', 'sts.cn-hangzhou.aliyuncs.com'),
            'durationSeconds' => env('ALIYUN_STS_DURATION_SECONDS', 3600),
            'externalId' => env('ALIYUN_STS_EXTERNAL_ID', 'external_id_test'),
        ],
        'ctyun' => [
            'driver' => CtyunStsAdapterFactory::class,
            'accessId' => env('CTYUN_STS_ACCESS_ID'),
            'accessSecret' => env('CTYUN_STS_ACCESS_SECRET'),
            'endpoint' => env('CTYUN_STS_ENDPOINT', 'oos-cn-iam.ctyunapi.cn'),
            'durationSeconds' => env('CTYUN_STS_DURATION_SECONDS', 3600),
        ],
    ],
];
```

## 使用





> ⚠️ **注意:**
>
> - 不同的平台对应的 `policy` 的写法略微不同。`getToken` 请自定义对应的 `policy` 进行传入。
> - 好的，我知道大家并不想写 `Policy`，但各位在对文件的访问控制的颗粒度如果需要到具体文件的程度,那就并不是写死的 `config` 就能搞定的了。
> - 所以我在 **`easySts`** 项目里 我接口中增加了各个不同的服务的 `policy` 的生成用接口。

### 默认配置
在 **`Hyperf`** 下可以使用,因为在 `ConfigProvider` 已经做了映射配置。
如果你想在 `Laravel` 或者别的框架中使用默认配置的写法的话,以 `Laravel` 举例，可以写一个 `ServiceProvider` 类，并在 `register` 方法中注册即可。
具体写法可以参照我另外一个包 [**Laravel-passport-cache**](https://github.com/ShaBaoFa/laravel-passport-cache/blob/main/src/CacheServiceProvider.php) 的 `CacheServiceProvider.php`
```php
use Wlfpanda1012\CommonSts\Sts;

$sts = new Sts();
$policy = [];
$sts->getToken()
$token = $sts->getToken($policy);
```

### 自定义配置
```php
use Wlfpanda1012\EasySts\StsFactory;

$sts = (new (StsFactory::class))->get('ctyun');
$policy = [];
$token = $sts->getToken($policy);
```

## Policy生成

> - 实现的接口
> - `public function storagePolicy(string $effect, array $actions, array|string $path, array $config = []): array`
> - effect = ALLOW/DENY actions = 根据不同的平台 actions 的写法不同，目前使用的是Aliyun OSS, path 可以为单一的string，如果是复数文件则用数组. config 里 可以写入其他配置（比如 桶名字,UID 等 具体配置参数不同平台的`key`不同，可以查看代码）

### Storage(文件存储)
```php
use Wlfpanda1012\EasySts\StsFactory;


$sts = new Sts();
$policy = $sts->storagePolicy(OSSEffect::ALLOW->value, [OSSAction::ALL_GET->value], $urls, $config);
$token = $sts->getToken($policy);
```