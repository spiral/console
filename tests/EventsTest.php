<?php

declare(strict_types=1);

namespace Spiral\Tests\Console;

use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Console\Event\CommandFinished;
use Spiral\Console\Event\CommandStarting;
use Spiral\Tests\Console\Fixtures\TestCommand;

final class EventsTest extends BaseTest
{
    public function testEventsShouldBeDispatched(): void
    {
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $dispatcher->expects(self::exactly(2))
            ->method('dispatch')
            ->with(
                $this->callback(
                    static fn(CommandStarting|CommandFinished $event): bool => $event->command instanceof TestCommand
                ),
            );

        $core = $this->getCore(
            locator: $this->getStaticLocator([
                new TestCommand(),
            ]),
            eventDispatcher: $dispatcher
        );

        $core->run('test');
    }
}
