<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console;

use Psr\Container\ContainerInterface;
use Spiral\Console\Config\ConsoleConfig;

final class StaticLocator implements LocatorInterface
{
    /** @var ConsoleConfig */
    private $config;

    /** @var ContainerInterface */
    private $container;

    /**
     * @param ConsoleConfig       $config
     * @param  ContainerInterface $container
     */
    public function __construct(ConsoleConfig $config, ContainerInterface $container)
    {
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function locateCommands(): array
    {
        $commands = [];
        foreach ($this->config->getCommands() as $command) {
            $commands[] = $this->container->get($command);
        }

        return $commands;
    }
}