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

class ConfigTest extends TestCase
{
    public function testLocateCommands()
    {
        $config = new ConsoleConfig();
        $this->assertFalse($config->locateCommands());

        $config = new ConsoleConfig(['locateCommands' => true]);
        $this->assertTrue($config->locateCommands());
    }
}