<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Console\Config\ConsoleConfig;
use Spiral\Console\Console;
use Spiral\Console\LocatorInterface;
use Spiral\Console\StaticLocator;
use Spiral\Console\Tests\Fixtures\User\UserCommand;
use Spiral\Core\Container;

abstract class BaseTest extends TestCase
{
    protected $container;

    const TOKENIZER_CONFIG = [
        'directories' => [__DIR__ . '/Fixtures/'],
        'exclude'     => ['User'],
    ];

    const CONFIG = [
        'locateCommands' => false,
        'commands'       => [
            UserCommand::class
        ]
    ];

    public function setUp()
    {
        $this->container = new Container();

        $this->container->bind(
            ConsoleConfig::class,
            new ConsoleConfig(static::CONFIG)
        );
    }

    protected function getCore(LocatorInterface $locator = null): Console
    {
        return new Console(
            $this->container->get(ConsoleConfig::class),
            $locator ?? new StaticLocator([], $this->container),
            $this->container
        );
    }
}