<?php

namespace Mosaic\CommandBus\Tests\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\CommandBus\CommandBus;
use Mosaic\CommandBus\Providers\TacticianProvider;
use Mosaic\Container\Container;

class TacticianProviderTest extends \PHPUnit_Framework_TestCase
{
    public function getDefinition() : DefinitionProviderInterface
    {
        return new TacticianProvider(\Mockery::mock(Container::class));
    }

    public function shouldDefine() : array
    {
        return [
            CommandBus::class
        ];
    }

    public function test_defines_all_required_contracts()
    {
        $definitions = $this->getDefinition()->getDefinitions();
        foreach ($this->shouldDefine() as $define) {
            $this->assertArrayHasKey($define, $definitions);
        }
    }
}
