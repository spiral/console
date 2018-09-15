<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

class OptionsTest extends BaseTest
{
    public function testOptions()
    {
        $core = $this->getCore();

        $this->assertSame(
            "no option",
            $core->run('optional')->getOutput()->fetch()
        );

        $this->assertSame(
            "hello",
            $core->run('optional', ['-o' => true, 'arg' => 'hello'])->getOutput()->fetch()
        );
    }
}