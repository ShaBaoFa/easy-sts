<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Wlfpanda1012\EasySts\Adapter\AliyunStsAdapterFactory;
use Wlfpanda1012\EasySts\Adapter\CtyunStsAdapterFactory;
use Wlfpanda1012\EasySts\Adapter\LocalStsAdapterFactory;

/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use function Hyperf\Support\env;

return [
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
