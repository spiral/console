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
use Spiral\Core\ContainerScope;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsoleCore
{
    // Undefined response code for command (errors). See below.
    const CODE_NONE = 102;

    /** @var ConsoleConfig */
    private $config;

    /** @var ContainerInterface */
    private $container;

    /** @var LocatorInterface */
    private $locator;

    /** @var Application|null */
    private $application = null;

    /**
     * @param ConsoleConfig      $config
     * @param ContainerInterface $container
     * @param LocatorInterface   $locator
     */
    public function __construct(
        ConsoleConfig $config,
        ContainerInterface $container,
        LocatorInterface $locator
    ) {
        $this->config = $config;
        $this->container = $container;
        $this->locator = $locator;
    }

    /**
     * Run console application.
     *
     * @param InputInterface|null  $input
     * @param OutputInterface|null $output
     * @return int
     *
     * @throws \Throwable
     */
    public function start(InputInterface $input = null, OutputInterface $output = null): int
    {
        return ContainerScope::runScope($this->container, function () use ($input, $output) {
            return $this->getApplication()->run($input, $output);
        });
    }

    /**
     * Run selected command.
     *
     * @param string               $command
     * @param InputInterface|array $input
     * @param OutputInterface|null $output
     * @return CommandOutput
     *
     * @throws \Throwable
     * @throws CommandNotFoundException
     */
    public function run(
        ?string $command,
        $input = [],
        OutputInterface $output = null
    ): CommandOutput {
        if (is_array($input)) {
            $input = new ArrayInput($input + compact('command'));
        }
        $output = $output ?? new BufferedOutput();

        $command = $this->getApplication()->find($command);
        $code = ContainerScope::runScope($this->container, function () use (
            $command,
            $input,
            $output
        ) {
            return $command->run($input, $output);
        });

        return new CommandOutput($code ?? self::CODE_NONE, $output);
    }

    /**
     * Get associated Symfony Console Application.
     *
     * @return Application
     *
     * @throws \Spiral\Console\Exception\LocatorException
     */
    public function getApplication(): Application
    {
        if (!empty($this->application)) {
            return $this->application;
        }

        $this->application = new Application($this->config->getName(), $this->config->getVersion());
        $this->application->setCatchExceptions(false);
        $this->application->setAutoExit(false);

        foreach ($this->locator->locateCommands() as $command) {
            $this->application->add($command);
        }

        if (!$this->locator instanceof StaticLocator) {
            // Register user defined commands
            $static = new StaticLocator($this->config, $this->container);
            foreach ($static->locateCommands() as $command) {
                $this->application->add($command);
            }
        }

        return $this->application;
    }
}