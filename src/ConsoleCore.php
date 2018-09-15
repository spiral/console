<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console;

use Psr\Container\ContainerInterface;
use Spiral\Console\Configs\ConsoleConfig;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCore
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
     * Run selected command.
     *
     * @param string               $command
     * @param InputInterface|array $input
     * @param OutputInterface|null $output
     * @return CommandOutput
     *
     * @throws \Exception
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

        // todo: run scoped?

        $command = $this->getApplication()->find($command);

        if ($command instanceof Command) {
            $code = $command->runScoped($this->container, $input, $output);
        } else {
            $code = $command->run($input, $output);
        }

        return new CommandOutput($code ?? self::CODE_NONE, $output);
    }

    /**
     * Get associated Symfony Console Application.
     *
     * @return Application
     *
     * @throws \Spiral\Console\Exceptions\LocatorException
     */
    public function getApplication(): Application
    {
        if (!empty($this->application)) {
            return $this->application;
        }

        $this->application = new Application($this->config->getName(), $this->config->getVersion());
        $this->application->setCatchExceptions(false);

        foreach ($this->locator->locateCommands() as $command) {
            $this->application->add($command);
        }

        // Register user defined commands
        if (!$this->locator instanceof StaticLocator) {
            $static = new StaticLocator($this->config, $this->container);
            foreach ($static as $command) {
                $this->application->add($command);
            }
        }

        return $this->application;
    }
}