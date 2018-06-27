<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Converter\Annotation\MethodInjectParamsHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class InjectMethodParamsHandlerTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider_isTagged
     */
    public function test_isTagged($status, $name)
    {
        //arrange
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'isTagged', $name);

        //assert
        $this->assertEquals($status, $result);
    }

    public function dataProvider_isTagged()
    {
        return array(
            array(
                'status' => true,
                'name' => '!tagged debug',
            ),
            array(
                'status' => false,
                'name' => '!tagged_something',
            ),
            array(
                'status' => false,
                'name' => 'debug',
            ),
        );
    }

    /**
     * @dataProvider dataProvider_isParameters
     */
    public function test_isParameters($status, $name)
    {
        //arrange
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'isParameters', $name);

        //assert
        $this->assertEquals($status, $result);
    }

    public function dataProvider_isParameters()
    {
        return array(
            array(
                'status' => true,
                'name' => '%debug%',
            ),
            array(
                'status' => false,
                'name' => '%debug',
            ),
            array(
                'status' => false,
                'name' => 'debug%',
            ),
            array(
                'status' => false,
                'name' => 'debug',
            ),
        );
    }

    /**
     * @dataProvider dataProvider_camelToSnake
     */
    public function test_camelToSnake($camel, $snake)
    {
        //arrange
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'camelToSnake', $camel);

        //assert
        $this->assertEquals($snake, $result);
    }

    public function dataProvider_camelToSnake()
    {
        return array(
            array(
                'camel' => 'AAbC',
                'snake' => 'a_ab_c',
            ),
            array(
                'camel' => '__AAbC',
                'snake' => 'a_ab_c',
            ),

            array(
                'camel' => 'RsDiExtraBundle',
                'snake' => 'rs_di_extra_bundle',
            ),
        );
    }

    public function test_convertAnnotationArguments_is_tagged()
    {
        //arrange
        $isTaggedIndex = 0;
        $injectParams = new InjectParams();
        $injectParams->params = array(
            'param0' => new Inject(),
            'param1' => new Inject(),
        );
        $injectParams->params['param0']->value = '!tagged value0';
        $injectParams->params['param0']->required = true;
        $injectParams->params['param1']->value = '!tagged value1';
        $injectParams->params['param1']->required = false;
        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('isTagged', 'isParameters'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('isTagged')
            ->willReturnCallback(function($param) use(&$isTaggedIndex, $injectParams){
                $this->assertEquals($injectParams->params["param{$isTaggedIndex}"]->value, $param);
                $isTaggedIndex++;
                return true;
            });

        $methodInjectParamsHandler
            ->expects($this->never())
            ->method('isParameters')
            ;

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'convertAnnotationArguments', $injectParams);

        //assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(TaggedIteratorArgument::class, $result['param0']);
        $this->assertInstanceOf(TaggedIteratorArgument::class, $result['param1']);
        $this->assertEquals('value0', $result['param0']->getTag());
        $this->assertEquals('value1', $result['param1']->getTag());
    }

    public function test_convertAnnotationArguments_is_parameters()
    {
        //arrange
        $isTaggedIndex = 0;
        $isParametersIndex = 0;
        $injectParams = new InjectParams();
        $injectParams->params = array(
            'param0' => new Inject(),
            'param1' => new Inject(),
        );
        $injectParams->params['param0']->value = '%value0%';
        $injectParams->params['param0']->required = true;
        $injectParams->params['param1']->value = '%value1%';
        $injectParams->params['param1']->required = false;

        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('isTagged', 'isParameters'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('isTagged')
            ->willReturnCallback(function($param) use(&$isTaggedIndex, $injectParams){
                $this->assertEquals($injectParams->params["param{$isTaggedIndex}"]->value, $param);
                $isTaggedIndex++;
                return false;
            });

        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('isParameters')
            ->willReturnCallback(function($param) use(&$isParametersIndex, $injectParams){
                $this->assertEquals($injectParams->params["param{$isParametersIndex}"]->value, $param);
                $isParametersIndex++;
                return true;
            });

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'convertAnnotationArguments', $injectParams);

        //assert
        $this->assertCount(2, $result);
        $this->assertEquals(array(
            'param0' => '%value0%',
            'param1' => '%value1%',
        ), $result);
    }

    public function test_convertAnnotationArguments_is_service()
    {
        //arrange
        $isTaggedIndex = 0;
        $isParametersIndex = 0;
        $injectParams = new InjectParams();
        $injectParams->params = array(
            'param0' => new Inject(),
            'param1' => new Inject(),
        );
        $injectParams->params['param0']->value = 'value0';
        $injectParams->params['param0']->required = true;
        $injectParams->params['param1']->value = 'value1';
        $injectParams->params['param1']->required = false;

        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('isTagged', 'isParameters'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('isTagged')
            ->willReturnCallback(function($param) use(&$isTaggedIndex, $injectParams){
                $this->assertEquals($injectParams->params["param{$isTaggedIndex}"]->value, $param);
                $isTaggedIndex++;
                return false;
            });

        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('isParameters')
            ->willReturnCallback(function($param) use(&$isParametersIndex, $injectParams){
                $this->assertEquals($injectParams->params["param{$isParametersIndex}"]->value, $param);
                $isParametersIndex++;
                return false;
            });

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'convertAnnotationArguments', $injectParams);

        //assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(Reference::class, $result['param0']);
        $this->assertEquals(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $result['param0']->getInvalidBehavior());
        $this->assertEquals('value0', (string) $result['param0']);
        $this->assertInstanceOf(Reference::class, $result['param1']);
        $this->assertEquals(ContainerInterface::IGNORE_ON_INVALID_REFERENCE, $result['param1']->getInvalidBehavior());
        $this->assertEquals('value1', (string) $result['param1']);
    }

    public function test_convertArguments()
    {
        //arrange
        $reflectionParameter0 = $this->getMockBuilder(\ReflectionParameter::class)
            ->setMethods(array('getName'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParameter0->expects($this->atLeastOnce())->method('getName')->willReturn('foo');
        $reflectionParameter1 = $this->getMockBuilder(\ReflectionParameter::class)
            ->setMethods(array('getName'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParameter1->expects($this->atLeastOnce())->method('getName')->willReturn('bar');
        $reflectionParameter2 = $this->getMockBuilder(\ReflectionParameter::class)
        ->setMethods(array('getName'))
        ->disableOriginalConstructor()
        ->getMock();
        $reflectionParameter2->expects($this->never())->method('getName')->willReturn('buz');

        $parameters = array(
            $reflectionParameter0,
            $reflectionParameter1,
            $reflectionParameter2,
        );

        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->setMethods(array('getParameters', 'getNumberOfRequiredParameters'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionMethod
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn($parameters);
        $reflectionMethod
            ->expects($this->atLeastOnce())
            ->method('getNumberOfRequiredParameters')
            ->willReturn(count($parameters) - 1);
        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('camelToSnake'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->at(0))
            ->method('camelToSnake')
            ->with('foo')
            ->willReturn('foo');
        $methodInjectParamsHandler
            ->expects($this->at(1))
            ->method('camelToSnake')
            ->with('bar')
            ->willReturn('bar');
        $methodInjectParamsHandler
            ->expects($this->exactly(2))
            ->method('camelToSnake');

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'convertArguments', $reflectionMethod);

        //assert
        $this->assertCount(2, $result);
        $this->assertInstanceOf(Reference::class, $result['foo']);
        $this->assertEquals('foo', (string)$result['foo']);
        $this->assertEquals(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $result['foo']->getInvalidBehavior());
        $this->assertInstanceOf(Reference::class, $result['bar']);
        $this->assertEquals('bar', (string)$result['bar']);
        $this->assertEquals(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $result['bar']->getInvalidBehavior());
    }

    public function test_mapArguments()
    {
        //arrange
        $arguments = array(
            'foo' => 'foo',
            'foo1' => 'foo1',
            'bar' => 'bar',
            'buz1' => 'buz1',
        );
        $reflectionParameter0 = $this->getMockBuilder(\ReflectionParameter::class)
            ->setMethods(array('getName'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParameter0->expects($this->atLeastOnce())->method('getName')->willReturn('foo');
        $reflectionParameter1 = $this->getMockBuilder(\ReflectionParameter::class)
            ->setMethods(array('getName'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParameter1->expects($this->atLeastOnce())->method('getName')->willReturn('bar');
        $reflectionParameter2 = $this->getMockBuilder(\ReflectionParameter::class)
            ->setMethods(array('getName'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParameter2->expects($this->atLeastOnce())->method('getName')->willReturn('buz');

        $parameters = array(
            $reflectionParameter0,
            $reflectionParameter1,
            $reflectionParameter2,
        );

        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->setMethods(array('getParameters'))
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionMethod
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn($parameters);
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'mapArguments', $reflectionMethod, $arguments);

        //assert
        $this->assertEquals(array('foo', 'bar'), $result);
    }

    public function test_findFactoryClassMeta_not_found()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta = new ClassMeta();
        $reflectionMethod = new \ReflectionMethod($this, 'test_findFactoryClassMeta_not_found');
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'findFactoryClassMeta', $classMeta, $reflectionMethod);

        //assert
        $this->assertFalse($result);
    }

    public function test_findFactoryClassMeta()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta->class = self::class;
        $classMeta->nextClassMeta->nextClassMeta->factoryMethod = array(self::class, 'test_findFactoryClassMeta_not_found');
        $reflectionMethod = new \ReflectionMethod($this, 'test_findFactoryClassMeta_not_found');
        $methodInjectParamsHandler = new MethodInjectParamsHandler();

        //act
        $result = $this->callObjectMethod($methodInjectParamsHandler, 'findFactoryClassMeta', $classMeta, $reflectionMethod);

        //assert
        $this->assertSame($classMeta->nextClassMeta->nextClassMeta, $result);
    }

    public function test_handle_isConstructor()
    {
        //arrange
        $annotation = new InjectParams();
        $factoryClassMeta = new ClassMeta();
        $classMeta = new ClassMeta();
        $arguments = array('foo' => 'bad', 'bar' => 'bar');
        $annotationArguments = array('foo' => 'foo', 'buz' => 'buz');
        $mappedArguments = array('foo', 'bar');
        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isConstructor'))
            ->getMock();

        $reflectionMethod
            ->expects($this->once())
            ->method('isConstructor')
            ->willReturn(true);

        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('findFactoryClassMeta', 'convertArguments', 'convertAnnotationArguments', 'mapArguments'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertArguments')
            ->with($reflectionMethod)
            ->willReturn($arguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertAnnotationArguments')
            ->with($annotation)
            ->willReturn($annotationArguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('mapArguments')
            ->with($reflectionMethod, array('foo' => 'foo', 'bar' => 'bar', 'buz' => 'buz'))
            ->willReturn($mappedArguments);
        $methodInjectParamsHandler
            ->expects($this->never())
            ->method('findFactoryClassMeta')
            ->with($classMeta, $reflectionMethod)
            ->willReturn($factoryClassMeta);

        //act
        $methodInjectParamsHandler->handle($classMeta, $reflectionMethod, $annotation);

        //assert
        $this->assertEquals($mappedArguments, $classMeta->arguments);
    }

    public function test_handle_isFactory()
    {
        //arrange
        $annotation = new InjectParams();
        $factoryClassMeta = new ClassMeta();
        $classMeta = new ClassMeta();
        $arguments = array('foo' => 'bad', 'bar' => 'bar');
        $annotationArguments = array('foo' => 'foo', 'buz' => 'buz');
        $mappedArguments = array('foo', 'bar');
        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isConstructor'))
            ->getMock();

        $reflectionMethod
            ->expects($this->once())
            ->method('isConstructor')
            ->willReturn(false);

        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('findFactoryClassMeta', 'convertArguments', 'convertAnnotationArguments', 'mapArguments'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertArguments')
            ->with($reflectionMethod)
            ->willReturn($arguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertAnnotationArguments')
            ->with($annotation)
            ->willReturn($annotationArguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('mapArguments')
            ->with($reflectionMethod, array('foo' => 'foo', 'bar' => 'bar', 'buz' => 'buz'))
            ->willReturn($mappedArguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('findFactoryClassMeta')
            ->with($classMeta, $reflectionMethod)
            ->willReturn($factoryClassMeta);

        //act
        $methodInjectParamsHandler->handle($classMeta, $reflectionMethod, $annotation);

        //assert
        $this->assertEquals($mappedArguments, $factoryClassMeta->arguments);
    }

    public function test_handle_is_methodCalls()
    {
        //arrange
        $annotation = new InjectParams();
        $factoryClassMeta = new ClassMeta();
        $classMeta = new ClassMeta();
        $arguments = array('foo' => 'bad', 'bar' => 'bar');
        $annotationArguments = array('foo' => 'foo', 'buz' => 'buz');
        $mappedArguments = array('foo', 'bar');
        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isConstructor', 'getName'))
            ->getMock();

        $reflectionMethod
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo');
        $reflectionMethod
            ->expects($this->once())
            ->method('isConstructor')
            ->willReturn(false);

        $methodInjectParamsHandler = $this->getMockBuilder(MethodInjectParamsHandler::class)
            ->setMethods(array('findFactoryClassMeta', 'convertArguments', 'convertAnnotationArguments', 'mapArguments'))
            ->getMock();

        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertArguments')
            ->with($reflectionMethod)
            ->willReturn($arguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('convertAnnotationArguments')
            ->with($annotation)
            ->willReturn($annotationArguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('mapArguments')
            ->with($reflectionMethod, array('foo' => 'foo', 'bar' => 'bar', 'buz' => 'buz'))
            ->willReturn($mappedArguments);
        $methodInjectParamsHandler
            ->expects($this->once())
            ->method('findFactoryClassMeta')
            ->with($classMeta, $reflectionMethod)
            ->willReturn(false);

        //act
        $methodInjectParamsHandler->handle($classMeta, $reflectionMethod, $annotation);

        //assert
        $this->assertEquals(array(
            array('foo', $mappedArguments),
        ), $classMeta->methodCalls);
    }
}
