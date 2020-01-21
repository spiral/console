<?php

/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Tests\Fixtures;

use Exception;
use Spiral\Console\Command;

class AnotherFailedCommand extends Command
{
    public const NAME = 'failed:another';

    /**
     * @throws Exception
     */
    public function perform(): void
    {
        throw new Exception('Unhandled another failed command error at ' . __METHOD__ . ' (line ' . __LINE__ . ')');
    }
}
