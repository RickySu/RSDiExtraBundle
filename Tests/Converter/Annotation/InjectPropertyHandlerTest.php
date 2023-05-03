<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Converter\Annotation\ParameterGuesser;
use RS\DiExtraBundle\Converter\Annotation\InjectPropertyHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class InjectPropertyHandlerTest extends BaseTestCase
{
    protected $property;

    public function test___controller()
    {
        //arrange

        //act
        $propertyInjectHandler = new InjectPropertyHandler();

        //assert
        $this->assertInstanceOf(ParameterGuesser::class, $this->getObjectAttribute($propertyInjectHandler, 'parameterGuesser'));
    }

    public function dataProvider_handle()
    {
        return array(
            array(
                'source' => array(
                    'id' => null,
                    'class' => null,
                ),
                'result' => array(
                    'id' => self::class,
                    'class' => self::class,
                ),
            ),
            array(
                'source' => array(
                    'id' => null,
                    'class' => 'foo',
                ),
                'result' => array(
                    'id' => 'foo',
                    'class' => 'foo',
                ),
            ),
            array(
                'source' => array(
                    'id' => 'bar',
                    'class' => 'foo',
                ),
                'result' => array(
                    'id' => 'bar',
                    'class' => 'foo',
                ),
            ),
        );
    }

    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle
     */
    public function test_handle_annotation_value_is_null($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $annotation = new Inject();
        $annotation->value = null;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->once())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->never())
            ->method('guessAnnotationArgument');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->properties[$reflectionProperty->getName()]);
    }

    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle
     */
    public function test_handle_annotation_value_is_not_null_required_false($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $annotation = new Inject();
        $annotation->value = 'bar';
        $annotation->required = false;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->never())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->once())
            ->method('guessAnnotationArgument')
            ->with($annotation->value, $annotation->required)
            ->willReturn('foo');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->properties[$reflectionProperty->getName()]);
    }


    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle
     */
    public function test_handle_annotation_value_is_not_null_required_true($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $annotation = new Inject();
        $annotation->value = 'bar';
        $annotation->required = true;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->never())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->once())
            ->method('guessAnnotationArgument')
            ->with($annotation->value, $annotation->required)
            ->willReturn('foo');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->properties[$reflectionProperty->getName()]);
    }

    public function dataProvider_handle_controller()
    {
        return array(
            array(
                'source' => array(
                    'id' => null,
                    'class' => null,
                ),
                'result' => array(
                    'id' => FakeController::class,
                    'class' => FakeController::class,
                ),
            ),
            array(
                'source' => array(
                    'id' => null,
                    'class' => 'foo',
                ),
                'result' => array(
                    'id' => 'foo',
                    'class' => 'foo',
                ),
            ),
            array(
                'source' => array(
                    'id' => 'bar',
                    'class' => 'foo',
                ),
                'result' => array(
                    'id' => 'bar',
                    'class' => 'foo',
                ),
            ),
        );
    }

    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle_controller
     */
    public function test_handle_annotation_value_is_null_controller($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $classMeta->isController = true;
        $annotation = new Inject();
        $annotation->value = null;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(FakeController::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->once())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->never())
            ->method('guessAnnotationArgument');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->controllerProperties[$reflectionProperty->getName()]);
    }

    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle_controller
     */
    public function test_handle_annotation_value_is_not_null_required_false_controller($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $classMeta->isController = true;
        $annotation = new Inject();
        $annotation->value = 'bar';
        $annotation->required = false;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(FakeController::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->never())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->once())
            ->method('guessAnnotationArgument')
            ->with($annotation->value, $annotation->required)
            ->willReturn('foo');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->controllerProperties[$reflectionProperty->getName()]);
    }


    /**
     * @param $source
     * @param $result
     * @throws \ReflectionException
     * @dataProvider dataProvider_handle_controller
     */
    public function test_handle_annotation_value_is_not_null_required_true_controller($source, $result)
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = $source['id'];
        $classMeta->class = $source['class'];
        $classMeta->isController = true;
        $annotation = new Inject();
        $annotation->value = 'bar';
        $annotation->required = true;
        $propertyInjectHandler = new InjectPropertyHandler();
        $reflectionProperty = new \ReflectionProperty(FakeController::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->onlyMethods(array('guessArgument', 'guessAnnotationArgument'))
            ->getMock();
        $parameterGuesser
            ->expects($this->never())
            ->method('guessArgument')
            ->with($reflectionProperty->getName())
            ->willReturn('foo');

        $parameterGuesser
            ->expects($this->once())
            ->method('guessAnnotationArgument')
            ->with($annotation->value, $annotation->required)
            ->willReturn('foo');

        $this->setObjectAttribute($propertyInjectHandler, 'parameterGuesser', $parameterGuesser);

        //act
        $propertyInjectHandler->handle($classMeta, $reflectionProperty, $annotation);

        //assert
        $this->assertEquals($result['id'], $classMeta->id);
        $this->assertEquals($result['class'], $classMeta->class);
        $this->assertEquals('foo', $classMeta->controllerProperties[$reflectionProperty->getName()]);
    }
}