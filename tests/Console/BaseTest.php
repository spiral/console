<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Console\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Console\CommandLocator;
use Spiral\Console\Configs\ConsoleConfig;
use Spiral\Console\ConsoleCore;
use Spiral\Core\BootloadManager;
use Spiral\Core\Container;
use Spiral\Core\MemoryInterface;
use Spiral\Core\NullMemory;
use Spiral\Tokenizer\Bootloaders\TokenizerBootloader;
use Spiral\Tokenizer\Configs\TokenizerConfig;

abstract class BaseTest extends TestCase
{
    protected $container;

    const TOKENIZER_CONFIG = [
        'directories' => [__DIR__ . '/Fixtures/'],
        'exclude'     => ['User'],
    ];

    const CONFIG = [
        'locateCommands' => false,
        'commands'       => []
    ];

    public function setUp()
    {
        $this->container = new Container();
        $this->container->bind(MemoryInterface::class, new NullMemory());

        $bootloder = new BootloadManager($this->container);
        $bootloder->bootload([TokenizerBootloader::class]);

        $this->container->bind(
            TokenizerConfig::class,
            new TokenizerConfig(static::TOKENIZER_CONFIG)
        );
        $this->container->bind(
            ConsoleConfig::class,
            new ConsoleConfig(static::CONFIG)
        );
    }

    protected function getCore(string $locator = CommandLocator::class): ConsoleCore
    {
        return new ConsoleCore(
            $this->container->get(ConsoleConfig::class),
            $this->container,
            $this->container->get($locator)
        );
    }
}