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
    public function testLocateCommands()
    {
        $config = new ConsoleConfig();
        $this->assertFalse($config->locateCommands());

        $config = new ConsoleConfig(['locateCommands' => true]);
        $this->assertTrue($config->locateCommands());
    }

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