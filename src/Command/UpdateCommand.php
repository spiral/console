<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Command;

use Psr\Container\ContainerInterface;
use Spiral\Console\Config\ConsoleConfig;

final class UpdateCommand extends SequenceCommand
{
    protected const NAME        = 'update';
    protected const DESCRIPTION = 'Update project state';

    /**
     * @param ConsoleConfig      $config
     * @param ContainerInterface $container
     * @return int
     */
    public function perform(ConsoleConfig $config, ContainerInterface $container): int
    {
        $this->writeln("<info>Updating project state:</info>\n");

        return $this->runSequence($config->updateSequence(), $container);
    }
}
