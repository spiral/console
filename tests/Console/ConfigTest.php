<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Console\Configs\ConsoleConfig;
use Spiral\Console\Sequences\CallableSequence;

class ConfigTest extends TestCase
{
    /**
     * @expectedException \Spiral\Console\Exceptions\ConfigException
     */
    public function testBadSequence()
    {
        $config = new ConsoleConfig([
            'updateSequence' => [
                $this
            ]
        ]);

        iterator_to_array($config->updateSequence());
    }


    public function testForcedSequence()
    {
        $config = new ConsoleConfig([
            'updateSequence' => [
                new CallableSequence("test")
            ]
        ]);

        $this->assertCount(1, iterator_to_array($config->updateSequence()));
    }
}