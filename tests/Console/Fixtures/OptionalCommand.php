<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests\Fixtures;

use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class OptionalCommand extends Command
{
    const NAME = 'optional';

    /**
     * {@inheritdoc}
     */
    const OPTIONS = [
        ['option', 'o', InputOption::VALUE_NONE, 'Use option']
    ];

    /**
     * {@inheritdoc}
     */
    const ARGUMENTS = [
        ['arg', InputArgument::OPTIONAL, 'Value'],
    ];

    public function perform()
    {
        $this->write(!$this->option('option') ? 'no option' : $this->argument('arg'));
    }
}