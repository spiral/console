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
            "Hello World!",
            $core->run('test')->getOutput()->fetch()
        );
    }
}