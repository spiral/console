<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Console\Sequence;

use Psr\Container\ContainerInterface;
use Spiral\Core\ResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Invokes service method as part of the sequence.
 */
final class CallableSequence extends AbstractSequence
{
    /** @var string */
    private $function;

    /** @var array */
    private $parameters = [];

    /**
     * @param callable $function
     * @param array    $parameters
     * @param string   $header
     * @param string   $footer
     */
    public function __construct(
        $function,
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
    public function execute(ContainerInterface $container, OutputInterface $output): void
    {
        $function = $this->function;
        if (is_string($function) && strpos($function, ':') !== false) {
            $function = explode(':', str_replace('::', ':', $function));
        }

        if (is_array($function) && isset($function[0]) && !is_object($function[0])) {
            $function[0] = $container->get($function[0]);
        }

        /** @var ResolverInterface $resolver */
        $resolver = $container->get(ResolverInterface::class);

        if (is_array($function)) {
            $reflection = new \ReflectionMethod($function[0], $function[1]);
            $reflection->invokeArgs($function[0], $resolver->resolveArguments($reflection, [
                'output' => $output
            ]));

            return;
        }

        $reflection = new \ReflectionFunction($function);
        $reflection->invokeArgs($resolver->resolveArguments($reflection, [
            'output' => $output
        ]));
    }
}
