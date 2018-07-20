<?php
namespace RS\DiExtraBundle\Tests\Funtional;

use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooNotPublicService;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooPublicService;

class ServiceExtendTest extends BaseKernelTestCase
{
    public function test_extend_service()
    {
        //arrange

        //act
        $service = self::$container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooPublicService::class, $service->fooPublicService);
        $this->assertInstanceOf(FooNotPublicService::class, $service->fooNotPublicService);
    }

    public function test_method_override()
    {
        //arrange

        //act
        $service = self::$container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $service->foo);
    }

    public function test_service_override()
    {
        //arrange

        //act
        $service = self::$container->get('extend_service');

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $service->foo2);
        $this->assertNull($service->foo2Origin);
    }
}
