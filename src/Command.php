<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Console;

use Spiral\Console\Traits\HelpersTrait;
use Spiral\Core\ContainerScope;
use Spiral\Core\Exception\ScopeException;
use Spiral\Core\ResolverInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides automatic command configuration and access to global container scope.
 */
abstract class Command extends SymfonyCommand
{
    use HelpersTrait;

    // Command name.
    protected const NAME = '';

    //  Short command description.
    protected const DESCRIPTION = '';

    // Command options specified in Symphony format. For more complex definitions redefine
    // getOptions() method.
    protected const OPTIONS = [];

    // Command arguments specified in Symphony format. For more complex definitions redefine
    // getArguments() method.
    protected const ARGUMENTS = [];

    /**
     * {@inheritdoc}
     *
     * Pass execution to "perform" method using container to resolve method dependencies.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = ContainerScope::getContainer();
        if (empty($container)) {
            throw new ScopeException("Unable to run SpiralCommand outside of IoC scope.");
        }

        $reflection = new \ReflectionMethod($this, 'perform');
        $reflection->setAccessible(true);

        /** @var ResolverInterface $resolver */
        $resolver = $container->get(ResolverInterface::class);

        try {
            list($this->input, $this->output) = [$input, $output];

            //Executing perform method with method injection
            return $reflection->invokeArgs($this, $resolver->resolveArguments(
                $reflection,
                compact('input', 'output')
            ));
        } finally {
            list($this->input, $this->output) = [null, null];
        }
    }

    /**
     * Configures the command.
     */
    protected function configure()
    {
        $this->setName(static::NAME);
        $this->setDescription(static::DESCRIPTION);

        foreach ($this->defineOptions() as $option) {
            call_user_func_array([$this, 'addOption'], $option);
        }

        foreach ($this->defineArguments() as $argument) {
            call_user_func_array([$this, 'addArgument'], $argument);
        }
    }

    /**
     * Define command options.
     *
     * @return array
     */
    protected function defineOptions(): array
    {
        return static::OPTIONS;
    }

    /**
     * Define command arguments.
     *
     * @return array
     */
    protected function defineArguments(): array
    {
        return static::ARGUMENTS;
    }
}