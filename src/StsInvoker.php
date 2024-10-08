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

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class StsInvoker
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $name = $config->get('sts.default', 'aliyun');
        $factory = $container->get(StsFactory::class);
        return $factory->get($name);
    }
}
