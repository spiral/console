<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Sequences;

use Psr\Container\ContainerInterface;
use Spiral\Core\ResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Invokes service method as part of the sequence.
 */
class CallableSequence extends AbstractSequence
{
    /** @var string */
    private $function;

    /** @var array */
    private $parameters = [];

    /**
     * @param string $function
     * @param array  $parameters
     * @param string $header
     * @param string $footer
     */
    public function __construct(
        string $function,
        array $parameters = [],
        string $header = '',
        string $footer = ''
    ) {
        $this->function = $function;
        $this->parameters = $parameters;

        parent::__construct($header, $footer);
    }

    /**
     * @inheritdoc
     */
    public function execute(ContainerInterface $container, OutputInterface $output)
    {
        $function = $this->function;
        if (is_string($function) && strpos($function, ':')) {
            $function = explode(':', str_replace('::', ':', $function));
        }

        if (is_array($function) && isset($function[0]) && !is_object($function[0])) {
            $function[0] = $container->get($function[0]);
        }

        if (is_array($function)) {
            $reflection = new \ReflectionMethod($function[0], $function[1]);
        } else {
            $reflection = new \ReflectionFunction($function);
        }

        /** @var ResolverInterface $resolver */
        $resolver = $container->get(ResolverInterface::class);

        $reflection->invokeArgs($resolver->resolveArguments($reflection, [
            'output' => $output
        ]));
    }
}