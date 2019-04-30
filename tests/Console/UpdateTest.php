<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Spiral\Console\Command\UpdateCommand;
use Spiral\Console\Console;
use Spiral\Console\StaticLocator;
use Spiral\Console\Tests\Fixtures\HelperCommand;
use Spiral\Console\Tests\Fixtures\TestCommand;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTest extends BaseTest
{
    const TOKENIZER_CONFIG = [
        'directories' => [__DIR__ . '/../../src/Command', __DIR__ . '/Fixtures/'],
        'exclude'     => []
    ];

    const CONFIG = [
        'locateCommands' => false,
        'commands'       => [],
        'update'         => [
            ['command' => 'test', 'header' => 'Test Command'],
            ['command' => 'helper', 'options' => ['helper' => 'writeln'], 'footer' => 'Good!'],
            ['invoke' => [self::class, 'do']],
            ['invoke' => self::class . '::do'],
            'Spiral\Console\Tests\ok',
            ['invoke' => self::class . '::err'],
        ]
    ];

    public function testConfigure()
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class,
            TestCommand::class,
            UpdateCommand::class
        ]));

        $this->container->bind(Console::class, $core);

        $result = $core->run('update')->getOutput()->fetch();

        $this->assertSame(str_replace(["\n", "\r", "  "], ' ', "Updating project state:

Test Command
Hello World - 0
hello
Good!

OK
OK
OK2
exception

All done!"), trim(str_replace(["\n", "\r", "  "], ' ', $result)));
    }

    public function do(OutputInterface $output)
    {
        $output->write("OK");
    }

    public function err(OutputInterface $output)
    {
        throw new ShortException();
    }
}