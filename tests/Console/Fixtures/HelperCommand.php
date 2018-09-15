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

class HelperCommand extends Command
{
    const NAME = 'helper';

    /**
     * {@inheritdoc}
     */
    const ARGUMENTS = [
        ['helper', InputArgument::REQUIRED, 'Helper'],
    ];

    public function perform()
    {
        switch ($this->argument('helper')) {
            case "verbose":
                $this->write($this->isVerbose() ? 'true' : 'false');
                break;
            case 'sprintf':
                $this->sprintf("%s world", 'hello');
                break;

            case 'writeln':
                $this->writeln('hello');
                break;

            case 'table':
                $table = $this->table(['id', 'value']);
                $table->addRow(['1', 'true']);
                $table->render();
        }
    }
}