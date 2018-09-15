<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Configs;

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

    // todo: sequences?
}