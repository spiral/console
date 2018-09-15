<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console;

use Symfony\Component\Console\Output\OutputInterface;

final class CommandOutput
{
    /** @var int */
    private $code = 0;

    /** @var OutputInterface */
    private $output = '';

    /**
     * @param int             $code
     * @param OutputInterface $output
     */
    public function __construct(int $code, OutputInterface $output)
    {
        $this->code = $code;
        $this->output = $output;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}