<?php

namespace Mosaic\CommandBus\Adapters\Tactician;

use ArrayAccess;
use League\Tactician\CommandBus as TacticianCommandBus;
use Mosaic\CommandBus\AbstractBus;
use Mosaic\CommandBus\CommandBus;
use Mosaic\Container\Container;
use Mosaic\Support\ArrayObject;

class TacticianBus extends AbstractBus implements CommandBus
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $middleware;

    /**
     * TacticianBus constructor.
     * @param Container $container
     * @param array     $middleware
     */
    public function __construct(Container $container, array $middleware = [])
    {
        $this->container  = $container;
        $this->middleware = $middleware;
    }

    /**
     * @param  object $command
     * @param  array  $middleware
     * @return mixed
     */
    public function dispatch($command, array $middleware = [])
    {
        return (new TacticianCommandBus(
            array_merge(
                $this->resolveMiddleware($middleware, $this->container),
                $this->middleware
            )
        ))->handle($command);
    }

    /**
     * @param  string      $command
     * @param  ArrayAccess $input
     * @param  array       $extra
     * @param  array       $middleware
     * @return mixed
     */
    public function dispatchFrom($command, ArrayAccess $input, array $extra = [], array $middleware = [])
    {
        return $this->dispatch(
            $this->marshal($command, $input, $extra),
            $middleware
        );
    }

    /**
     * @param  string $command
     * @param  array  $input
     * @param  array  $middleware
     * @return mixed
     */
    public function dispatchFromArray($command, array $input = [], array $middleware = [])
    {
        return $this->dispatch(
            $this->marshal($command, new ArrayObject(), $input),
            $middleware
        );
    }
}
