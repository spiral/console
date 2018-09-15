<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests\Fixtures;

use Spiral\Console\Command;

class TestCommand extends Command
{
    const NAME = 'test';

    public function perform()
    {
        $this->write("Hello World!");
    }
}