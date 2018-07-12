<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Converter\Annotation\ParameterGuesser;
use RS\DiExtraBundle\Converter\Annotation\PropertyInjectHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class PropertyInjectHandlerTest extends BaseTestCase
{
    protected $property;

    public function test___controller()
    {
        //arrange

        //act
        $propertyInjectHandler = new PropertyInjectHandler();

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
        $propertyInjectHandler = new PropertyInjectHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->setMethods(array('guessArgument', 'guessAnnotationArgument'))
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
        $propertyInjectHandler = new PropertyInjectHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->setMethods(array('guessArgument', 'guessAnnotationArgument'))
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
        $propertyInjectHandler = new PropertyInjectHandler();
        $reflectionProperty = new \ReflectionProperty(self::class, 'property');
        $parameterGuesser = $this->getMockBuilder(ParameterGuesser::class)
            ->setMethods(array('guessArgument', 'guessAnnotationArgument'))
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