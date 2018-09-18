<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console;

use Psr\Container\ContainerInterface;
use Spiral\Console\Command\ReloadCommand;
use Spiral\Core\MemoryInterface;
use Spiral\Tokenizer\ClassesInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Automatically locates available commands using ClassLocator.
 */
final class CommandLocator implements LocatorInterface
{
    // Memory section to store commands cache
    const MEMORY = 'commands';

    /** @var ContainerInterface */
    private $container;

    /** @var ClassesInterface */
    private $classes;

    /** @var MemoryInterface */
    private $memory;

    /**
     * @param ContainerInterface $container
     * @param ClassesInterface   $classes
     * @param MemoryInterface    $memory
     */
    public function __construct(
        ContainerInterface $container,
        ClassesInterface $classes,
        MemoryInterface $memory
    ) {
        $this->classes = $classes;
        $this->memory = $memory;
        $this->container = $container;
    }

    /**
     * Reset memory cache.
     */
    public function reset()
    {
        $this->memory->saveData(static::MEMORY, []);
    }

    /**
     * {@inheritdoc}
     */
    public function locateCommands(): array
    {
        $commands = [];
        foreach ($this->getClasses() as $command) {
            $commands[] = $this->container->get($command);
        }

        return $commands;
    }

    /**
     * Get available command class names.
     *
     * @return array
     */
    protected function getClasses(): array
    {
        $commands = (array)$this->memory->loadData(static::MEMORY);

        if (!empty($commands)) {
            return array_filter($commands, 'class_exists');
        }

        foreach ($this->classes->getClasses(SymfonyCommand::class) as $class) {
            if ($class->isAbstract()) {
                continue;
            }

            $commands[] = $class->getName();
        }

        // Required to reload command cache
        $commands[] = ReloadCommand::class;

        $this->memory->saveData(static::MEMORY, $commands);

        return $commands;
    }
}