<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use Spiral\Console\StaticLocator;
use Spiral\Console\Tests\Fixtures\OptionalCommand;

class OptionsTest extends BaseTest
{
    public function testOptions()
    {
        $core = $this->getCore(new StaticLocator([
            OptionalCommand::class
        ]));

        $this->assertSame(
            "no option",
            $core->run('optional')->getOutput()->fetch()
        );

        $this->assertSame(
            "hello",
            $core->run('optional', ['-o' => true, 'arg' => 'hello'])->getOutput()->fetch()
        );

        $this->assertSame(
            0,
            $core->run('optional', ['-o' => true, 'arg' => 'hello'])->getCode()
        );
    }
}