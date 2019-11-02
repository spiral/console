<?php

/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Console\Tests\Fixtures\User\UserCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ScopeTest extends TestCase
{
    /**
     * @expectedException \Spiral\Core\Exception\ScopeException
     */
    public function testScopeError(): void
    {
        $c = new UserCommand();
        $c->run(new ArrayInput([]), new BufferedOutput());
    }
}
