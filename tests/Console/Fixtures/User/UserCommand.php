<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests\Fixtures\User;

use Spiral\Console\Command;

class UserCommand extends Command
{
    const NAME        = 'test:user';
    const DESCRIPTION = 'Test Command';

    private $count = 0;

    public function perform()
    {
        $this->write("Hello User");
    }
}