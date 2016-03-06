<?php

namespace Mosaic\Tests\CommandBus\Tactician;

use League\Tactician\Exception\MissingHandlerException;
use Mockery;
use Mockery\Mock;
use Mosaic\CommandBus\Adapters\Tactician\HandlerLocator;
use PHPUnit_Framework_TestCase;

class HandlerLocatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    protected $container;

    /**
     * @var HandlerLocator
     */
    protected $locator;

    public function setUp()
    {
        $this->locator = new HandlerLocator(
            $this->container = Mockery::mock(\Mosaic\Container\Container::class)
        );
    }

    public function test_can_get_handler_of_command()
    {
        $this->container->shouldReceive('make')->with(ExampleCommandHandler::class)->andReturn(new ExampleCommandHandler);

        $handler = $this->locator->getHandlerForCommand(ExampleCommand::class);

        $this->assertInstanceOf(ExampleCommandHandler::class, $handler);
    }

    public function test_will_throw_exception_when_cannot_locate_the_handler()
    {
        $this->setExpectedException(MissingHandlerException::class, 'Missing handler for command WrongCommand');

        $this->locator->getHandlerForCommand('WrongCommand');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}

class ExampleCommand
{
}

class ExampleCommandHandler
{
}
