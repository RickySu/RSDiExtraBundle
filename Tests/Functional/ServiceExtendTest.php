<?php
namespace RS\DiExtraBundle\Tests\Functional;

use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooNotPublicService;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooPublicService;

class ServiceExtendTest extends BaseKernelTestCase
{
    public function test_extend_service()
    {
        //arrange

        //act
        $service = $this->container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooPublicService::class, $service->fooPublicService);
        $this->assertInstanceOf(FooNotPublicService::class, $service->fooNotPublicService);
    }

    public function test_method_override()
    {
        //arrange

        //act
        $service = $this->container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $service->foo);
    }

    public function test_service_override()
    {
        //arrange

        //act
        $service = $this->container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $service->foo2);
        $this->assertNull($service->foo2Origin);
    }

    public function test_traits_inject()
    {
        //arrange

        //act
        $service = $this->container->get('extend_service');

        //assert
        $this->assertEquals('foo_static_factory', $service->fooStaticFactory->params['id']);
        $this->assertEquals('foo_static_factory2', $service->fooStaticFactory2->params['id']);
    }
}
