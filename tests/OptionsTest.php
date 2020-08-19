<?php

/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Tests\Console;

use Spiral\Console\StaticLocator;
use Spiral\Tests\Console\Fixtures\OptionalCommand;

class OptionsTest extends BaseTest
{
    public function testOptions(): void
    {
        $core = $this->getCore(new StaticLocator([
            OptionalCommand::class
        ]));

        $this->assertSame(
            'no option',
            $core->run('optional')->getOutput()->fetch()
        );

        $this->assertSame(
            'hello',
            $core->run('optional', ['-o' => true, 'arg' => 'hello'])->getOutput()->fetch()
        );

        $this->assertSame(
            0,
            $core->run('optional', ['-o' => true, 'arg' => 'hello'])->getCode()
        );
    }
}
