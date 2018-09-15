<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use PHPUnit\Framework\MockObject\Matcher\InvokedAtLeastOnce;
use Spiral\Console\CommandLocator;
use Spiral\Console\Tests\Fixtures\User\UserCommand;
use Spiral\Core\MemoryInterface;
use Spiral\Tokenizer\ClassesInterface;

class LocatorTest extends BaseTest
{
    public function testReset()
    {
        $l = $this->container->get(CommandLocator::class);
        $this->assertNotEmpty($l->locateCommands());
        $l->reset();
        $this->assertNotEmpty($l->locateCommands());
    }

    public function testClassFilter()
    {
        $m = $this->createMock(MemoryInterface::class);
        $m->method('loadData')->willReturn([
            UserCommand::class,
            "InvalidClass"
        ]);

        $l = new CommandLocator(
            $this->container,
            $this->createMock(ClassesInterface::class),
            $m
        );

        $this->assertCount(1, $l->locateCommands());
    }
}