<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests\Fixtures;

use Spiral\Console\Command;
use Spiral\Core\Container\SingletonInterface;

class TestCommand extends Command implements SingletonInterface
{
    const NAME = 'test';

    private $count = 0;

    public function perform()
    {
        $this->write("Hello World - " . ($this->count++));
    }
}