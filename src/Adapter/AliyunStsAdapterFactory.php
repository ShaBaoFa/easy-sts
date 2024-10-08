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

namespace Wlfpanda1012\EasySts\Adapter;

use Wlfpanda1012\AliyunSts\StsService;
use Wlfpanda1012\EasySts\Contract\StsAdapterFactoryInterface;
use Wlfpanda1012\EasySts\Exception\InvalidArgumentException;

class AliyunStsAdapterFactory implements StsAdapterFactoryInterface
{
    public function make(array $options)
    {
        if (! isset($options['accessId']) || ! isset($options['accessSecret'])) {
            throw new InvalidArgumentException();
        }
        $options['access_key_id'] = $options['accessId'];
        $options['access_key_secret'] = $options['accessSecret'];
        $options['role_arn'] = $options['roleArn'];
        return new StsService($options);
    }
}
