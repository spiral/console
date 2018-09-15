<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Configs;

use Spiral\Console\Exceptions\ConfigException;
use Spiral\Console\SequenceInterface;
use Spiral\Console\Sequences\CallableSequence;
use Spiral\Console\Sequences\CommandSequence;
use Spiral\Core\InjectableConfig;

class ConsoleConfig extends InjectableConfig
{
    const CONFIG = 'console';

    /**
     * @var array
     */
    protected $config = [
        'locateCommands' => true,
        'commands'       => [],
        'configure'      => [],
        'update'         => []
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Spiral Framework";
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return "UNKNOWN";
    }

    /**
     * Indication that ConsoleDispatcher must locate commands automatically.
     *
     * @return bool
     */
    public function locateCommands(): bool
    {
        return !empty($this->config['locateCommands']);
    }

    /**
     * User defined set of commands (to be used when auto-location is off).
     *
     * @return array
     */
    public function getCommands(): array
    {
        if (!array_key_exists('commands', $this->config)) {
            //Legacy config support
            return [];
        }

        return $this->config['commands'];
    }

    /**
     * Get list of configure sequences.
     *
     * @return \Generator|SequenceInterface[]
     *
     * @throws ConfigException
     */
    public function configureSequence(): \Generator
    {
        $sequence = $this->config['configure'] ?? $this->config['configureSequence'] ?? [];
        foreach ($sequence as $item) {
            yield $this->parseSequence($item);
        }
    }

    /**
     * Get list of all update sequences.
     *
     * @return \Generator|SequenceInterface[]
     *
     * @throws ConfigException
     */
    public function updateSequence(): \Generator
    {
        $sequence = $this->config['update'] ?? $this->config['updateSequence'] ?? [];
        foreach ($sequence as $item) {
            yield $this->parseSequence($item);
        }
    }

    /**
     * @param mixed $item
     * @return SequenceInterface
     *
     * @throws ConfigException
     */
    protected function parseSequence($item): SequenceInterface
    {
        if ($item instanceof SequenceInterface) {
            return $item;
        }

        if (is_string($item)) {
            return new CallableSequence($item);
        }

        if (isset($item['command'])) {
            return new CommandSequence(
                $item['command'],
                $item['options'] ?? [],
                $item['header'] ?? '',
                $item['footer'] ?? ''
            );
        }

        if (isset($item['call'])) {
            return new CallableSequence(
                $item['call'],
                $item['parameters'] ?? [],
                $item['header'] ?? '',
                $item['footer'] ?? ''
            );
        }

        throw new ConfigException(sprintf(
            "Unable to parse sequence `%s`.",
            json_encode($item)
        ));
    }
}