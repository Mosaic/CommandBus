<?php

namespace Mosaic\CommandBus\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Mosaic\CommandBus\Adapters\Tactician\HandlerLocator;
use Mosaic\CommandBus\Adapters\Tactician\TacticianBus;
use Mosaic\CommandBus\CommandBus;
use Mosaic\Container\Container;

class TacticianDefinition implements DefinitionProviderInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the definition to register in the container.
     *
     * Definitions must be indexed by their entry ID. For example:
     *
     *     return [
     *         'logger' => ...
     *         'mailer' => ...
     *     ];
     *
     * @return array
     */
    public function getDefinitions()
    {
        return [
            CommandBus::class => function () {
                return new TacticianBus(
                    $this->container,
                    [
                        new CommandHandlerMiddleware(
                            new ClassNameExtractor(),
                            new HandlerLocator($this->container),
                            new HandleInflector()
                        )
                    ]
                );
            }
        ];
    }
}
