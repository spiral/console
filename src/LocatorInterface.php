<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console;

use Spiral\Console\Exception\LocatorException;
use Symfony\Component\Console\Command\Command as CommandAlias;

interface LocatorInterface
{
    /**
     * Get all available command class names.
     *
     * @return CommandAlias[]
     *
     * @throws LocatorException
     */
    public function locateCommands(): array;
}
