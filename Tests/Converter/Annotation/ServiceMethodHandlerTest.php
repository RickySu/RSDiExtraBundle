<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Converter\Annotation\ServiceMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\Reference;

class ServiceMethodHandlerTest extends BaseTestCase
{
    public function test_handle_empty_nextClassMeta()
    {
        //arrange
        $service = new Service();
        $serviceHandler = new ServiceMethodHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_empty_nextClassMeta'), $service);

        //assert
        $this->assertInstanceOf(ClassMeta::class, $classMeta->nextClassMeta);
    }

    public function test_handle_has_nextClassMeta()
    {
        //arrange
        $service = new Service();
        $serviceHandler = new ServiceMethodHandler();
        $nextClassMeta = new ClassMeta();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->nextClassMeta = $nextClassMeta;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_empty_nextClassMeta'), $service);

        //assert
        $this->assertInstanceOf(ClassMeta::class, $classMeta->nextClassMeta);
        $this->assertSame($nextClassMeta, $classMeta->nextClassMeta->nextClassMeta);
    }

    public function test_handle_id_null()
    {
        //arrange
        $service = new Service();
        $serviceHandler = new ServiceMethodHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_id_null'), $service);

        //assert
        $this->assertEquals(self::class.'.test_handle_id_null', $classMeta->nextClassMeta->id);
    }

    public function test_handle_static_factory()
    {
        //arrange
        $service = new Service();
        $service->id = 'id';
        $service->parent = 'parent';
        $service->public = true;
        $service->private = true;
        $service->shared = true;
        $service->deprecated = true;
        $service->decorates = 'devorates';
        $service->decorationInnerName = 'decorationInnerName';
        $service->decorationPriority = 10;
        $service->abstract = true;
        $service->environments = array('prod', 'dev');
        $service->autowire = true;
        $service->synthetic = true;
        $service->lazy = true;
        $service->autoconfigured = true;

        $serviceHandler = new ServiceMethodHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_id_null'), $service);

        //assert
        $this->assertEquals($service->id, $classMeta->nextClassMeta->id);
        $this->assertEquals($service->parent, $classMeta->nextClassMeta->parent);
        $this->assertEquals($service->public, $classMeta->nextClassMeta->public);
        $this->assertEquals($service->private, $classMeta->nextClassMeta->private);
        $this->assertEquals($service->shared, $classMeta->nextClassMeta->shared);
        $this->assertEquals($service->deprecated, $classMeta->nextClassMeta->deprecated);
        $this->assertEquals($service->decorates, $classMeta->nextClassMeta->decorates);
        $this->assertEquals($service->decorationInnerName, $classMeta->nextClassMeta->decorationInnerName);
        $this->assertEquals($service->decorationPriority, $classMeta->nextClassMeta->decorationPriority);
        $this->assertEquals($service->abstract, $classMeta->nextClassMeta->abstract);
        $this->assertEquals($service->environments, $classMeta->nextClassMeta->environments);
        $this->assertEquals($service->autowire, $classMeta->nextClassMeta->autowire);
        $this->assertEquals($service->synthetic, $classMeta->nextClassMeta->synthetic);
        $this->assertEquals($service->lazy, $classMeta->nextClassMeta->lazy);
        $this->assertEquals($service->autoconfigured, $classMeta->nextClassMeta->autoconfigured);
        $this->assertEquals(array(self::class, 'test_handle_id_null'), $classMeta->nextClassMeta->factoryMethod);
    }

    public function test_handle_service_factory()
    {
        //arrange
        $service = new Service();
        $service->id = 'id';
        $service->parent = 'parent';
        $service->public = true;
        $service->private = true;
        $service->shared = true;
        $service->deprecated = true;
        $service->decorates = 'devorates';
        $service->decorationInnerName = 'decorationInnerName';
        $service->decorationPriority = 10;
        $service->abstract = true;
        $service->environments = array('prod', 'dev');
        $service->autowire = true;
        $service->synthetic = true;
        $service->lazy = true;
        $service->autoconfigured = true;

        $serviceHandler = new ServiceMethodHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        //act
        $serviceHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_id_null'), $service);

        //assert
        $this->assertEquals($service->id, $classMeta->nextClassMeta->id);
        $this->assertEquals($service->parent, $classMeta->nextClassMeta->parent);
        $this->assertEquals($service->public, $classMeta->nextClassMeta->public);
        $this->assertEquals($service->private, $classMeta->nextClassMeta->private);
        $this->assertEquals($service->shared, $classMeta->nextClassMeta->shared);
        $this->assertEquals($service->deprecated, $classMeta->nextClassMeta->deprecated);
        $this->assertEquals($service->decorates, $classMeta->nextClassMeta->decorates);
        $this->assertEquals($service->decorationInnerName, $classMeta->nextClassMeta->decorationInnerName);
        $this->assertEquals($service->decorationPriority, $classMeta->nextClassMeta->decorationPriority);
        $this->assertEquals($service->abstract, $classMeta->nextClassMeta->abstract);
        $this->assertEquals($service->environments, $classMeta->nextClassMeta->environments);
        $this->assertEquals($service->autowire, $classMeta->nextClassMeta->autowire);
        $this->assertEquals($service->synthetic, $classMeta->nextClassMeta->synthetic);
        $this->assertEquals($service->lazy, $classMeta->nextClassMeta->lazy);
        $this->assertEquals($service->autoconfigured, $classMeta->nextClassMeta->autoconfigured);
        $this->assertInstanceOf(Reference::class, $classMeta->nextClassMeta->factoryMethod[0]);
        $this->assertEquals(array('foo', 'test_handle_id_null'), $classMeta->nextClassMeta->factoryMethod);
    }
}
