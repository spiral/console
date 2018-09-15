<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Spiral\Console\CommandLocator;

class LocatorTest extends BaseTest
{
    public function testReset()
    {
        $l = $this->container->get(CommandLocator::class);
        $this->assertNotEmpty($l->locateCommands());
        $l->reset();
        $this->assertNotEmpty($l->locateCommands());
    }
}