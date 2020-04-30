<?php
namespace RS\DiExtraBundle\Tests\Funtional;
use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooChildService;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooNotPublicService;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\FooPublicService;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\ServiceFactory;

class ServiceTest extends BaseKernelTestCase
{
    public function test_parameters_exclude_files()
    {
        //arrange
        //act
        $files = self::$container->getParameter('rs_di_extra.exclude_files');

        //assert
        $this->assertEquals(array(
            '*Test.php'
        ), $files);
    }

    public function test_parameters_exclude_directories()
    {
        //arrange
        //act
        $dirs = self::$container->getParameter('rs_di_extra.exclude_directories');

        //assert
        $this->assertEquals(array(
            'foo',
            'bar'
        ), $dirs);
    }

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
        $this->assertEquals('foo_static_factory', $result['id']);
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
        $this->assertEquals('foo_static_factory2', $result['id']);
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
        $this->assertEquals('foo_static_factory3', $result['id']);
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
        $this->assertEquals('foo_service_factory', $result['id']);
    }

    public function test_taggedService_foo()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');
        $ids = array_map(function($service){
            return $service->params['id'];
        }, $service->fooTagServices);
        sort($ids);

        //assert
        $this->assertCount(4, $ids);
        $this->assertEquals(array(
            'foo_service_factory',
            'foo_static_factory',
            'foo_static_factory2',
            'foo_static_factory3'
        ), $ids);
    }

    public function test_taggedService_bar()
    {
        //arrange
        //act
        $service = self::$container->get('foo_public');
        $ids = array_map(function($service){
            return $service->params['id'];
        }, $service->barTagServices);
        sort($ids);

        //assert
        $this->assertCount(3, $ids);
        $this->assertEquals(array(
            'foo_not_public',
            'foo_static_factory',
            'foo_static_factory2',
        ), $ids);
    }

    public function test_childService_issue_3()
    {
        //arrange
        //act
        $service = self::$container->get(ServiceFactory::class);

        //assert
        $this->assertInstanceOf(FooChildService::class, $service->fooChild);
    }
}
