<?php

/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Tests;

use Spiral\Console\Command\ConfigureCommand;
use Spiral\Console\Console;
use Spiral\Console\StaticLocator;
use Spiral\Console\Tests\Fixtures\HelperCommand;
use Spiral\Console\Tests\Fixtures\TestCommand;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureTest extends BaseTest
{
    public const TOKENIZER_CONFIG = [
        'directories' => [__DIR__ . '/../../src/Command', __DIR__ . '/Fixtures/'],
        'exclude'     => []
    ];

    public const CONFIG = [
        'locateCommands' => false,
        'configure'      => [
            ['command' => 'test', 'header' => 'Test Command'],
            ['command' => 'helper', 'options' => ['helper' => 'writeln'], 'footer' => 'Good!'],
            ['invoke' => [self::class, 'do']],
            ['invoke' => self::class . '::do'],
            'Spiral\Console\Tests\ok',
            ['invoke' => self::class . '::err'],
        ]
    ];

    public function testConfigure(): void
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class,
            ConfigureCommand::class,
            TestCommand::class
        ]));
        $this->container->bind(Console::class, $core);

        $result = $core->run('configure')->getOutput()->fetch();

        $this->assertSame(str_replace(["\n", "\r", '  '], ' ', 'Configuring project:

Test Command
Hello World - 0
hello
Good!

OK
OK
OK2
exception

All done!'), trim(str_replace(["\n", "\r", '  '], ' ', $result)));
    }

    public function do(OutputInterface $output): void
    {
        $output->write('OK');
    }

    public function err(OutputInterface $output): void
    {
        throw new ShortException();
    }
}

function ok(OutputInterface $output): void
{
    $output->write('OK2');
}
