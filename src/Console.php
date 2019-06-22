<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console;

use Psr\Container\ContainerInterface;
use Spiral\Console\Config\ConsoleConfig;
use Spiral\Core\Container;
use Spiral\Core\ContainerScope;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class Console
{
    // Undefined response code for command (errors). See below.
    public const CODE_NONE = 102;

    /** @var ConsoleConfig */
    private $config;

    /** @var LocatorInterface|null */
    private $locator;

    /** @var ContainerInterface */
    private $container;

    /** @var Application|null */
    private $application = null;

    /**
     * @param ConsoleConfig      $config
     * @param ContainerInterface $container
     * @param LocatorInterface   $locator
     */
    public function __construct(
        ConsoleConfig $config,
        LocatorInterface $locator = null,
        ContainerInterface $container = null
    ) {
        $this->config = $config;
        $this->locator = $locator;
        $this->container = $container ?? new Container();
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
        $input = $input ?? new ArgvInput();
        $output = $output ?? new ConsoleOutput();

        return ContainerScope::runScope($this->container, function () use ($input, $output) {
            return $this->run(
                $input->getFirstArgument() ?? 'list',
                $input,
                $output
            )->getCode();
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

        $this->configureIO($input, $output);

        $command = $this->getApplication()->find($command);
        $code = ContainerScope::runScope($this->container, function () use (
            $command,
            $input,
            $output
        ) {
            if ($command instanceof Command) {
                $command->setContainer($this->container);
            }

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

        if (!is_null($this->locator)) {
            foreach ($this->locator->locateCommands() as $command) {
                $this->application->add($command);
            }
        }

        // Register user defined commands
        $static = new StaticLocator($this->config->getCommands(), $this->container);
        foreach ($static->locateCommands() as $command) {
            $this->application->add($command);
        }

        return $this->application;
    }

    /**
     * Extracted in order to manage command lifecycle.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @see Application::configureIO()
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        if (true === $input->hasParameterOption(['--ansi'], true)) {
            $output->setDecorated(true);
        } elseif (true === $input->hasParameterOption(['--no-ansi'], true)) {
            $output->setDecorated(false);
        }

        if (true === $input->hasParameterOption(['--no-interaction', '-n'], true)) {
            $input->setInteractive(false);
        } elseif (\function_exists('posix_isatty')) {
            $inputStream = null;

            if ($input instanceof StreamableInputInterface) {
                $inputStream = $input->getStream();
            }

            if (!@posix_isatty($inputStream) && false === getenv('SHELL_INTERACTIVE')) {
                $input->setInteractive(false);
            }
        }

        switch ($shellVerbosity = (int)getenv('SHELL_VERBOSITY')) {
            case -1:
                $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
                break;
            case 1:
                $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
                break;
            case 2:
                $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
                break;
            case 3:
                $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
                break;
            default:
                $shellVerbosity = 0;
                break;
        }

        if (true === $input->hasParameterOption(['--quiet', '-q'], true)) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
            $shellVerbosity = -1;
        } else {
            if ($input->hasParameterOption('-vvv', true) || $input->hasParameterOption('--verbose=3',
                    true) || 3 === $input->getParameterOption('--verbose', false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
                $shellVerbosity = 3;
            } elseif ($input->hasParameterOption('-vv', true) || $input->hasParameterOption('--verbose=2',
                    true) || 2 === $input->getParameterOption('--verbose', false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
                $shellVerbosity = 2;
            } elseif ($input->hasParameterOption('-v', true) || $input->hasParameterOption('--verbose=1',
                    true) || $input->hasParameterOption('--verbose', true) || $input->getParameterOption('--verbose',
                    false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
                $shellVerbosity = 1;
            }
        }

        if (-1 === $shellVerbosity) {
            $input->setInteractive(false);
        }

        putenv('SHELL_VERBOSITY=' . $shellVerbosity);
        $_ENV['SHELL_VERBOSITY'] = $shellVerbosity;
        $_SERVER['SHELL_VERBOSITY'] = $shellVerbosity;
    }
}