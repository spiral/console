<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Sequence;

use Psr\Container\ContainerInterface;
use Spiral\Console\Console;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Executes command as part of the sequence.
 */
final class CommandSequence extends AbstractSequence
{
    /** @var string */
    private $command;

    /** @var array */
    private $options = [];

    /**
     * @param string $command
     * @param array  $options
     * @param string $header
     * @param string $footer
     */
    public function __construct(
        string $command,
        array $options = [],
        string $header = '',
        string $footer = ''
    ) {
        $this->command = $command;
        $this->options = $options;

        parent::__construct($header, $footer);
    }

    /**
     * @inheritdoc
     */
    public function execute(ContainerInterface $container, OutputInterface $output): void
    {
        /** @var Console $console */
        $console = $container->get(Console::class);

        $console->run($this->command, $this->options, $output);
    }
}
