<?php
namespace RS\DiExtraBundle\Tests\Generator\Factory;

use RS\DiExtraBundle\Generator\Factory\ControllerGenerator;
use RS\DiExtraBundle\Tests\BaseTestCase;
use RS\DiExtraBundle\Tests\Fixtures\Foo\Bar\Bar1;

class ControllerGeneratorTest extends BaseTestCase
{
    public function test___construct()
    {
        //arrange
        $className = 'foo';
        $constructParameters = array('foo1', 'bar1');
        $injectParameters = array('foo2', 'bar2');
        $propertyParameters = array('foo3', 'bar3');
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array('initParameters'))
            ->getMock();
        $generator
            ->expects($this->once())
            ->method('initParameters')
            ;

        $reflect = new \ReflectionClass(ControllerGenerator::class);
        $constructor = $reflect->getConstructor();

        //act
        $constructor->invoke($generator, $className, $constructParameters, $injectParameters, $propertyParameters);

        //assert
        $this->assertEquals($className, $this->getObjectAttribute($generator, 'className'));
        $this->assertEquals($constructParameters, $this->getObjectAttribute($generator, 'constructParameters'));
        $this->assertEquals($injectParameters, $this->getObjectAttribute($generator, 'injectParameters'));
        $this->assertEquals($propertyParameters, $this->getObjectAttribute($generator, 'propertyParameters'));
    }

    public function test_getFactoryClassName()
    {
        //arrange
        $className = 'Foo\\Bar\\Buz';

        $generator = new ControllerGenerator($className);

        //act
        $result = $generator->getFactoryClassName();

        //assert
        $this->assertEquals('Foo_Bar_Buz_'.$this->getObjectAttribute($generator, 'classSuffix'), $result);
    }

    public function test_getFactoryNamespace()
    {
        //arrange
        $className = 'Foo\\Bar\\Buz';

        $generator = new ControllerGenerator($className);

        //act
        $result = $this->callObjectMethod($generator, 'getFactoryNamespace');

        //assert
        $this->assertEquals('RS\\DiExtraBundle\\Factory\\Controller', $result);
    }

    public function test_getFactoryClassFullName()
    {
        //arrange
        $namespace = 'foo';
        $className = 'bar';
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->onlyMethods(array('getFactoryNamespace', 'getFactoryClassName'))
            ->disableOriginalConstructor()
            ->getMock();
        $generator
            ->expects($this->once())
            ->method('getFactoryNamespace')
            ->willReturn($namespace);
        $generator
            ->expects($this->once())
            ->method('getFactoryClassName')
            ->willReturn($className);

        //act
        $result = $generator->getFactoryClassFullName();

        //assert
        $this->assertEquals("$namespace\\$className", $result);
    }

    public function test_getDefine()
    {
        //arrange
        $random = md5(microtime().rand());
        $className = Bar1::class;
        $constructParameters = array('foo', 'bar', 'buz');
        $injectParameters = array(
            'inject1' => array('foo1', 'bar1', 'buz1'),
            'inject2' => array('foo2', 'bar2', 'buz2'),
        );
        $propertyParameters = array('foo', 'bar', 'buz');
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->onlyMethods(array('getFactoryNamespace'))
            ->setConstructorArgs(array($className, $constructParameters, $injectParameters, $propertyParameters))
            ->getMock();
        $generator
            ->expects($this->atLeastOnce())
            ->method('getFactoryNamespace')
            ->willReturn('RS\\DiExtra\\Test\\MockNamespace'.$random);
        $classFactory = $generator->getFactoryClassFullName();

        //act
        $tmpName = tempnam(sys_get_temp_dir(), 'rs_di_extra_getDefine');
        $define = $generator->getDefine();
        file_put_contents($tmpName, $define);
        require_once $tmpName;
        unlink($tmpName);
        $result = $classFactory::create('foo', 'bar', 'buz', 'foo1', 'bar1', 'buz1', 'foo2', 'bar2', 'buz2', 'foo', 'bar', 'buz');

        //assert
        $this->assertInstanceOf($className, $result);
        $this->assertEquals('foo', $this->getObjectAttribute($result, 'foo'));
        $this->assertEquals('bar', $this->getObjectAttribute($result, 'bar'));
        $this->assertEquals('buz', $this->getObjectAttribute($result, 'buz'));
        $this->assertEquals($constructParameters, $this->getObjectAttribute($result, 'constructParams'));
        $this->assertEquals($injectParameters['inject1'], $this->getObjectAttribute($result, 'inject1Params'));
        $this->assertEquals($injectParameters['inject2'], $this->getObjectAttribute($result, 'inject2Params'));
    }

    public function test_getDefine_null_parameters()
    {
        //arrange
        $random = md5(microtime().rand());
        $className = Bar1::class;
        $constructParameters = array('foo', 'bar', 'buz');
        $injectParameters = array(
            'inject1' => array('foo1', 'bar1', 'buz1'),
            'inject2' => array('foo2', 'bar2', 'buz2'),
        );
        $propertyParameters = array();
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->onlyMethods(array('getFactoryNamespace'))
            ->setConstructorArgs(array($className, $constructParameters, $injectParameters, $propertyParameters))
            ->getMock();
        $generator
            ->expects($this->atLeastOnce())
            ->method('getFactoryNamespace')
            ->willReturn('RS\\DiExtra\\Test\\MockNamespace'.$random);
        $classFactory = $generator->getFactoryClassFullName();

        //act
        $tmpName = tempnam(sys_get_temp_dir(), 'rs_di_extra_getDefine');
        $define = $generator->getDefine();
        file_put_contents($tmpName, $define);
        require_once $tmpName;
        unlink($tmpName);
        $result = $classFactory::create('foo', 'bar', 'buz', 'foo1', 'bar1', 'buz1', 'foo2', 'bar2', 'buz2');

        //assert
        $this->assertInstanceOf($className, $result);
        $this->assertNull($this->getObjectAttribute($result, 'foo'));
        $this->assertNull($this->getObjectAttribute($result, 'bar'));
        $this->assertNull($this->getObjectAttribute($result, 'buz'));
        $this->assertEquals($constructParameters, $this->getObjectAttribute($result, 'constructParams'));
        $this->assertEquals($injectParameters['inject1'], $this->getObjectAttribute($result, 'inject1Params'));
        $this->assertEquals($injectParameters['inject2'], $this->getObjectAttribute($result, 'inject2Params'));
    }
}