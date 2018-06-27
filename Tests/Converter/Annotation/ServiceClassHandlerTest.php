<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Converter\Annotation\ServiceClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ServiceClassHandlerTest extends BaseTestCase
{
    public function test_handle_id_null()
    {
        //arrange
        $service = new Service();
        $serviceHandler = new ServiceClassHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionClass($this), $service);

        //assert
        $this->assertEquals(self::class, $classMeta->id);
    }

    public function test_handle()
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

        $serviceHandler = new ServiceClassHandler();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        //act
        $serviceHandler->handle($classMeta, new \ReflectionClass($this), $service);

        //assert
        $this->assertEquals($service->id, $classMeta->id);
        $this->assertEquals($service->parent, $classMeta->parent);
        $this->assertEquals($service->public, $classMeta->public);
        $this->assertEquals($service->private, $classMeta->private);
        $this->assertEquals($service->shared, $classMeta->shared);
        $this->assertEquals($service->deprecated, $classMeta->deprecated);
        $this->assertEquals($service->decorates, $classMeta->decorates);
        $this->assertEquals($service->decorationInnerName, $classMeta->decorationInnerName);
        $this->assertEquals($service->decorationPriority, $classMeta->decorationPriority);
        $this->assertEquals($service->abstract, $classMeta->abstract);
        $this->assertEquals($service->environments, $classMeta->environments);
        $this->assertEquals($service->autowire, $classMeta->autowire);
        $this->assertEquals($service->synthetic, $classMeta->synthetic);
        $this->assertEquals($service->lazy, $classMeta->lazy);
        $this->assertEquals($service->autoconfigured, $classMeta->autoconfigured);
    }

}
