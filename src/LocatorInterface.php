<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console;

interface LocatorInterface
{
    /**
     * Get all available command class names.
     *
     * @return \Symfony\Component\Console\Command\Command[]
     *
     * @throws \Spiral\Console\Exceptions\LocatorException
     */
    public function locateCommands(): array;
}