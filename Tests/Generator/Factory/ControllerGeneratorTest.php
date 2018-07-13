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
        $parameters = array('foo', 'bar');

        //act
        $generator = new ControllerGenerator($className, $parameters);

        //assert
        $this->assertEquals($className, $this->getObjectAttribute($generator, 'className'));
        $this->assertEquals($parameters, $this->getObjectAttribute($generator, 'parameters'));
    }

    public function test_getFactoryClassName()
    {
        //arrange
        $className = 'Foo\\Bar\\Buz';
        $parameters = array('foo', 'bar');

        $generator = new ControllerGenerator($className, $parameters);

        //act
        $result = $generator->getFactoryClassName();

        //assert
        $this->assertEquals('Foo_Bar_Buz', $result);
    }

    public function test_getFactoryNamespace()
    {
        //arrange
        $className = 'Foo\\Bar\\Buz';
        $parameters = array('foo', 'bar');

        $generator = new ControllerGenerator($className, $parameters);

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
            ->setMethods(array('getFactoryNamespace', 'getFactoryClassName'))
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
        $parameters = array('foo', 'bar', 'buz');
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->setMethods(array('getFactoryNamespace'))
            ->setConstructorArgs(array($className, $parameters))
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
        $result = $classFactory::create('foo', 'bar', 'buz');

        //assert
        $this->assertInstanceOf($className, $result);
        $this->assertEquals('foo', $this->getObjectAttribute($result, 'foo'));
        $this->assertEquals('bar', $this->getObjectAttribute($result, 'bar'));
        $this->assertEquals('buz', $this->getObjectAttribute($result, 'buz'));
    }

    public function test_getDefine_null_parameters()
    {
        //arrange
        $random = md5(microtime().rand());
        $className = Bar1::class;
        $parameters = array();
        $generator = $this->getMockBuilder(ControllerGenerator::class)
            ->setMethods(array('getFactoryNamespace'))
            ->setConstructorArgs(array($className, $parameters))
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
        $result = $classFactory::create('foo', 'bar', 'buz');

        //assert
        $this->assertInstanceOf($className, $result);
        $this->assertNull($this->getObjectAttribute($result, 'foo'));
        $this->assertNull($this->getObjectAttribute($result, 'bar'));
        $this->assertNull($this->getObjectAttribute($result, 'buz'));
    }
}