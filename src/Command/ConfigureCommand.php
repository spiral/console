<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Command;

use Psr\Container\ContainerInterface;
use Spiral\Console\Command;
use Spiral\Console\Config\ConsoleConfig;

class ConfigureCommand extends Command
{
    const NAME        = 'configure';
    const DESCRIPTION = 'Configure project';

    /**
     * @param ConsoleConfig      $config
     * @param ContainerInterface $container
     */
    public function perform(ConsoleConfig $config, ContainerInterface $container)
    {
        $this->writeln("<info>Configuring project:</info>\n");

        foreach ($config->configureSequence() as $sequence) {
            $sequence->writeHeader($this->output);

            try {
                $sequence->execute($container, $this->output);
                $sequence->whiteFooter($this->output);
            } catch (\Throwable $e) {
                $this->sprintf("<error>%s</error>\n", $e);
            }

            $this->writeln("");
        }

        $this->writeln("<info>All done!</info>");
    }
}