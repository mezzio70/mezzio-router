<?php

declare(strict_types=1);

namespace MezzioTest\Router\Middleware;

use Mezzio\Router\Exception\MissingDependencyException;
use Mezzio\Router\Middleware\ImplicitOptionsMiddlewareFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ImplicitOptionsMiddlewareFactoryTest extends TestCase
{
    /** @var ContainerInterface&MockObject */
    private $container;

    /** @var ImplicitOptionsMiddlewareFactory */
    private $factory;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->factory   = new ImplicitOptionsMiddlewareFactory();
    }

    public function testFactoryRaisesExceptionIfResponseFactoryServiceIsMissing(): void
    {
        $this->expectException(MissingDependencyException::class);
        ($this->factory)($this->container);
    }

    public function testFactoryProducesImplicitOptionsMiddlewareWhenAllDependenciesPresent(): void
    {
        $factory = static function (): void {
        };

        $this->container
            ->method('has')
            ->withConsecutive([ResponseFactoryInterface::class], [ResponseInterface::class])
            ->willReturnOnConsecutiveCalls(false, true);

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(ResponseInterface::class)
            ->willReturn($factory);

        ($this->factory)($this->container);
    }
}
