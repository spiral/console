<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

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

//    public function testList()
//    {
//        $core = $this->getCore();
//        dump(
//            $core->run(null)->getOutput()->fetch()
//        );
//    }
}