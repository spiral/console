<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Spiral\Console\ConsoleCore;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTest extends BaseTest
{
    const TOKENIZER_CONFIG = [
        'directories' => [__DIR__ . '/../../src/Commands', __DIR__ . '/Fixtures/'],
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
            ['invoke' => 'Spiral\Console\Tests\ok']
        ]
    ];

    public function testConfigure()
    {
        $core = $this->getCore();
        $this->container->bind(ConsoleCore::class, $core);

        $result = $core->run('update')->getOutput()->fetch();

        $this->assertSame(str_replace(["\n", "\r", "  "], ' ', "Updating project state:

Test Command
Hello World - 0
hello
Good!

OK
OK
OK2
All done!"), trim(str_replace(["\n", "\r", "  "], ' ', $result)));
    }

    public function do(OutputInterface $output)
    {
        $output->write("OK");
    }
}