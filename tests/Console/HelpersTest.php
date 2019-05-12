<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Spiral\Console\StaticLocator;
use Spiral\Console\Tests\Fixtures\HelperCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class HelpersTest extends BaseTest
{
    public function testVerbose()
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class
        ]));

        $this->assertContains(
            "false",
            $core->run('helper', ['helper' => 'verbose'])->getOutput()->fetch()
        );

        $output = new BufferedOutput();
        $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        $this->assertContains(
            "true",
            $core->run(
                'helper',
                ['helper' => 'verbose', '-v' => true],
                $output
            )->getOutput()->fetch()
        );


    }

    public function testSprinf()
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class
        ]));

        $this->assertContains(
            "hello world",
            $core->run('helper', ['helper' => 'sprintf'])->getOutput()->fetch()
        );
    }

    public function testWriteln()
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class
        ]));

        $this->assertContains(
            "\n",
            $core->run('helper', ['helper' => 'writeln'])->getOutput()->fetch()
        );
    }

    public function testTable()
    {
        $core = $this->getCore(new StaticLocator([
            HelperCommand::class
        ]));

        $this->assertContains(
            "id",
            $core->run('helper', ['helper' => 'table'])->getOutput()->fetch()
        );

        $this->assertContains(
            "value",
            $core->run('helper', ['helper' => 'table'])->getOutput()->fetch()
        );

        $this->assertContains(
            "1",
            $core->run('helper', ['helper' => 'table'])->getOutput()->fetch()
        );

        $this->assertContains(
            "true",
            $core->run('helper', ['helper' => 'table'])->getOutput()->fetch()
        );
    }
}