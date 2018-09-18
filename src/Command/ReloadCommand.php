<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Command;

use Spiral\Console\Command;
use Spiral\Console\CommandLocator;

/**
 * Re-index available console commands.
 */
class ReloadCommand extends Command
{
    const NAME        = 'console:reload';
    const DESCRIPTION = 'Re-index console commands';

    /**
     * @param CommandLocator $locator
     */
    public function perform(CommandLocator $locator)
    {
        $locator->reset();

        $this->sprintf(
            "Console commands re-indexed, <comment>%s</comment> commands found.",
            count($locator->locateCommands())
        );
    }
}