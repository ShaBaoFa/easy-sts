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

namespace Wlfpanda1012\EasySts;

use Wlfpanda1012\CommonSts\Sts;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                Sts::class => StsInvoker::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for HyperfFlysystem.',
                    'source' => __DIR__ . '/../publish/sts.php',
                    'destination' => dirname(__DIR__, 1) . '/config/autoload/sts.php',
                ],
            ],
        ];
    }
}
