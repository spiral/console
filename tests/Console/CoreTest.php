<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CoreTest extends BaseTest
{
    public function testWelcome()
    {
        $core = $this->getCore();
        $this->assertSame(
            "Hello World - 0",
            $core->run('test')->getOutput()->fetch()
        );

        $this->assertSame(
            "Hello World - 1",
            $core->run('test')->getOutput()->fetch()
        );
    }

    public function testStart()
    {
        $core = $this->getCore();

        $output = new BufferedOutput();

        $core->start(new ArrayInput([]), $output);
        $output = $output->fetch();

        $this->assertContains("Spiral Framework", $output);
        $this->assertContains("console:reload", $output);
        $this->assertContains("Test Command", $output);
    }
}