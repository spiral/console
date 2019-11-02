<?php

/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Console\Config\ConsoleConfig;
use Spiral\Console\Sequence\CallableSequence;

class ConfigTest extends TestCase
{
    /**
     * @expectedException \Spiral\Console\Exception\ConfigException
     */
    public function testBadSequence(): void
    {
        $config = new ConsoleConfig([
            'updateSequence' => [
                $this
            ]
        ]);

        iterator_to_array($config->updateSequence());
    }

    public function testForcedSequence(): void
    {
        $config = new ConsoleConfig([
            'updateSequence' => [
                new CallableSequence('test')
            ]
        ]);

        $this->assertCount(1, iterator_to_array($config->updateSequence()));
    }
}
