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
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Wlfpanda1012\CommonSts\Contract\StsAdapter;
use Wlfpanda1012\CommonSts\Sts;
use Wlfpanda1012\EasySts\Adapter\LocalStsAdapterFactory;
use Wlfpanda1012\EasySts\Contract\StsAdapterFactoryInterface;
use Wlfpanda1012\EasySts\Exception\InvalidArgumentException;

class StsFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(private ContainerInterface $container, private ConfigInterface $config)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(string $name): Sts
    {
        $options = $this->config->get('sts', [
            'default' => 'local',
            'adapter' => [
                'local' => [
                    'driver' => LocalStsAdapterFactory::class,
                ],
            ],
        ]);
        $adapter = $this->getAdapter($options, $name);
        return new Sts($adapter, $options['adapter'][$name] ?? []);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAdapter(array $options, string $name): StsAdapter
    {
        if (! $options['adapter'] || ! $options['adapter'][$name]) {
            throw new InvalidArgumentException("sts configurations are missing {$name} options");
        }
        /**
         * @var StsAdapterFactoryInterface $driver
         */
        $driver = $this->container->get($options['adapter'][$name]['driver']);
        return $driver->make($options['adapter'][$name]);
    }
}
