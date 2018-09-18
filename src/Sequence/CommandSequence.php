<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Sequence;

use Psr\Container\ContainerInterface;
use Spiral\Console\ConsoleCore;
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
    public function execute(ContainerInterface $container, OutputInterface $output)
    {
        /** @var ConsoleCore $console */
        $console = $container->get(ConsoleCore::class);

        $console->run($this->command, $this->options, $output);
    }
}