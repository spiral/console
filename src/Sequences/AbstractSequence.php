<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Sequences;

use Spiral\Console\SequenceInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSequence implements SequenceInterface
{
    /** @var string */
    private $header;

    /** @var string */
    private $footer;

    /**
     * @param string $header
     * @param string $footer
     */
    public function __construct(string $header, string $footer)
    {
        $this->header = $header;
        $this->footer = $footer;
    }

    /**
     * @inheritdoc
     */
    public function writeHeader(OutputInterface $output)
    {
        if (!empty($this->header)) {
            $output->writeln($this->header);
        }
    }

    /**
     * @inheritdoc
     */
    public function whiteFooter(OutputInterface $output)
    {
        if (!empty($this->footer)) {
            $output->writeln($this->footer);
        }
    }
}