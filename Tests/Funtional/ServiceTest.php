<?php
namespace RS\DiExtraBundle\Tests\Funtional;
use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooNotPublicService;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooPublicService;

class ServiceTest extends BaseKernelTestCase
{
    public function test_foo_public_service()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');

        //assert
        $this->assertInstanceOf(FooPublicService::class, $service);
    }

    public function test_foo_public_service_alias()
    {
        //arrange
        //act
        $service = self::$container->get(FooPublicService::class);

        //assert
        $this->assertInstanceOf(FooPublicService::class, $service);
    }

    public function test_foo_not_public_service_with_id()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');

        //assert
        $this->assertFalse(in_array('foo_not_public', self::$container->getServiceIds()));
        $this->assertInstanceOf(FooNotPublicService::class, $service->fooNotPublicWithId);
    }

    public function test_foo_not_public_service_auto()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');

        //assert
        $this->assertFalse(in_array('foo_not_public', self::$container->getServiceIds()));
        $this->assertInstanceOf(FooNotPublicService::class, $service->fooNotPublic);
    }

    public function test___constructInject()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');
        $result = $service->constructParams;

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function test_injectMethodInject()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');
        $result = $service->injectParams;

        //assert
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function test_staticFactoryService()
    {
        //arrange
        //act
        $service = self::$container->get('foo_static_factory');
        $result = $service->params;

        //assert
        $this->assertInstanceOf(\stdClass::class, $service);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function test_staticFactoryService2()
    {
        //arrange
        //act
        $service = self::$container->get('foo_static_factory2');
        $result = $service->params;

        //assert
        $this->assertInstanceOf(\stdClass::class, $service);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function test_staticFactoryService3()
    {
        //arrange
        //act
        $service = self::$container->get('foo_static_factory3');
        $result = $service->params;

        //assert
        $this->assertInstanceOf(\stdClass::class, $service);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function test_serviceFactoryService()
    {
        //arrange
        //act
        $service = self::$container->get('foo_service_factory');
        $result = $service->params;

        //assert
        $this->assertInstanceOf(\stdClass::class, $service);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublicService']);
        $this->assertInstanceOf(FooNotPublicService::class, $result['fooNotPublic']);
        $this->assertEquals('bar', $result['foo']);
    }

}
