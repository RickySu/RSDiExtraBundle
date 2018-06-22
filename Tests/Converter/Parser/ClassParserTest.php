<?php
namespace RS\DiExtraBundle\Tests\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Parser\ClassParser;
use RS\DiExtraBundle\Converter\Parser\MethodParser;
use RS\DiExtraBundle\Converter\Parser\PropertyParser;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ClassParserTest extends BaseTestCase
{
    public function test___construct()
    {
        //arrange
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass(self::class);

        //act
        $parser = new ClassParser($annotationReader, $reflectionClass);

        //assert
        $this->assertSame($annotationReader, $this->getObjectAttribute($parser, 'annotationReader'));
        $this->assertSame($reflectionClass, $this->getObjectAttribute($parser, 'reflectionClass'));
    }

    public function test_parse()
    {
        //arrange
        $classMeta = new ClassMeta();

        $parser =  $this->getMockBuilder(ClassParser::class)
            ->setMethods(array(
                'parseClass',
                'parseMethod',
                'parseProperty'
            ))
            ->disableOriginalConstructor()
            ->getMock();

        $parser
            ->expects($this->once())
            ->method('parseClass')
            ->with($classMeta);

        $parser
            ->expects($this->once())
            ->method('parseMethod')
            ->with($classMeta);

        $parser
            ->expects($this->once())
            ->method('parseProperty')
            ->with($classMeta);

        //act
        $parser->parse($classMeta);

        //assert
    }

    public function test_parseMethod()
    {
        //arrange
        $classMeta = new ClassMeta();
        $reflectionMethods = array(
            $this->getMockBuilder(\ReflectionMethod::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(\ReflectionMethod::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(\ReflectionMethod::class)->disableOriginalConstructor()->getMock(),
        );

        $reflectionClass = $this->getMockBuilder(\ReflectionClass::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getMethods'))
            ->getMock();

        $reflectionClass
            ->expects($this->once())
            ->method('getMethods')
            ->willReturn($reflectionMethods);

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('createMethodParser'))
            ->getMock();

        $classParser
            ->expects($this->at(0))
            ->method('createMethodParser')
            ->willReturnCallback(function(\ReflectionMethod $reflectionMethod) use($classMeta, $reflectionMethods){
                $this->assertSame($reflectionMethods[0], $reflectionMethod);

                $methodParser = $this->getMockBuilder(MethodParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $methodParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $methodParser;
            });

        $classParser
            ->expects($this->at(1))
            ->method('createMethodParser')
            ->willReturnCallback(function(\ReflectionMethod $reflectionMethod) use($classMeta, $reflectionMethods){
                $this->assertSame($reflectionMethods[1], $reflectionMethod);

                $methodParser = $this->getMockBuilder(MethodParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $methodParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $methodParser;
            });

        $classParser
            ->expects($this->at(2))
            ->method('createMethodParser')
            ->willReturnCallback(function(\ReflectionMethod $reflectionMethod) use($classMeta, $reflectionMethods){
                $this->assertSame($reflectionMethods[2], $reflectionMethod);

                $methodParser = $this->getMockBuilder(MethodParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $methodParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $methodParser;
            });
        $classParser
            ->expects($this->exactly(3))
            ->method('createMethodParser')
        ;

        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);
        $this->setObjectAttribute($classParser, 'annotationReader', $annotationReader);

        //act
        $this->callObjectMethod($classParser, 'parseMethod', $classMeta);

        //assert
    }

    public function test_parseProperty()
    {
        //arrange
        $classMeta = new ClassMeta();
        $reflectionProperties = array(
            $this->getMockBuilder(\ReflectionProperty::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(\ReflectionProperty::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(\ReflectionProperty::class)->disableOriginalConstructor()->getMock(),
        );

        $reflectionClass = $this->getMockBuilder(\ReflectionClass::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getProperties'))
            ->getMock();

        $reflectionClass
            ->expects($this->once())
            ->method('getProperties')
            ->willReturn($reflectionProperties);

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('createPropertyParser'))
            ->getMock();

        $classParser
            ->expects($this->at(0))
            ->method('createPropertyParser')
            ->willReturnCallback(function(\ReflectionProperty $reflectionProperty) use($classMeta, $reflectionProperties){
                $this->assertSame($reflectionProperties[0], $reflectionProperty);

                $propertyParser = $this->getMockBuilder(PropertyParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $propertyParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $propertyParser;
            });
        $classParser
            ->expects($this->at(1))
            ->method('createPropertyParser')
            ->willReturnCallback(function(\ReflectionProperty $reflectionProperty) use($classMeta, $reflectionProperties){
                $this->assertSame($reflectionProperties[1], $reflectionProperty);

                $propertyParser = $this->getMockBuilder(PropertyParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $propertyParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $propertyParser;
            });
        $classParser
            ->expects($this->at(2))
            ->method('createPropertyParser')
            ->willReturnCallback(function(\ReflectionProperty $reflectionProperty) use($classMeta, $reflectionProperties){
                $this->assertSame($reflectionProperties[2], $reflectionProperty);

                $propertyParser = $this->getMockBuilder(PropertyParser::class)
                    ->disableOriginalConstructor()
                    ->setMethods(array('parse'))
                    ->getMock();

                $propertyParser
                    ->expects($this->once())
                    ->method('parse')
                    ->with($classMeta);

                return $propertyParser;
            });

        $classParser
            ->expects($this->exactly(3))
            ->method('createPropertyParser')
        ;

        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);
        $this->setObjectAttribute($classParser, 'annotationReader', $annotationReader);

        //act
        $this->callObjectMethod($classParser, 'parseProperty', $classMeta);

        //assert
    }

    public function test_parseClass()
    {
        //arrange
        $classMeta = new ClassMeta();

        $reflectionClass = $this->getMockBuilder(\ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionClass
            ->expects($this->any())
            ->method('getName')
            ->willReturn('MockClass');

        $annotations = array(
            $this->getMockBuilder(ClassProcessorInterface::class)->setMethods(array('handleClass'))->getMockForAbstractClass(),
            new \stdClass(),
            $this->getMockBuilder(ClassProcessorInterface::class)->setMethods(array('handleClass'))->getMockForAbstractClass(),
        );

        $annotations[0]
            ->expects($this->once())
            ->method('handleClass')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionClass $reflectionClassForTest) use($classMeta, $reflectionClass){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionClass, $reflectionClassForTest);
                $this->assertEquals('MockClass', $classMeta->class);
            })
            ;

        $annotations[2]
            ->expects($this->once())
            ->method('handleClass')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionClass $reflectionClassForTest) use($classMeta, $reflectionClass){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionClass, $reflectionClassForTest);
                $this->assertEquals('MockClass', $classMeta->class);
            })
        ;

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getClassAnnotations'))
            ->getMock();

        $annotationReader
            ->expects($this->once())
            ->method('getClassAnnotations')
            ->with($reflectionClass)
            ->willReturn($annotations);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('parseParent'))
            ->getMock();

        $classParser
            ->expects($this->once())
            ->method('parseParent')
            ->with($classMeta);

        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);
        $this->setObjectAttribute($classParser, 'annotationReader', $annotationReader);

        //act
        $this->callObjectMethod($classParser, 'parseClass', $classMeta);

        //assert
    }

    public function test_parseParent_no_parent_class()
    {
        //arrange
        $classMeta = new ClassMeta();
        $reflectionClass = $this->getMockBuilder(\ReflectionClass::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getParentClass'))
            ->getMock();
        $reflectionClass
            ->expects($this->once())
            ->method('getParentClass')
            ->willReturn(null);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('createClassParser', 'parse'))
            ->getMock();

        $classParser
            ->expects($this->never())
            ->method('createClassParser')
            ->with($reflectionClass)
            ->willReturn($classParser);

        $classParser
            ->expects($this->never())
            ->method('parse')
            ->with($classMeta)
            ;
        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);

        //act
        $this->callObjectMethod($classParser, 'parseParent', $classMeta);
        //assert
    }

    public function test_parseParent()
    {
        //arrange
        $classMeta = new ClassMeta();
        $reflectionClass = $this->getMockBuilder(\ReflectionClass::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getParentClass'))
            ->getMock();
        $reflectionClass
            ->expects($this->once())
            ->method('getParentClass')
            ->willReturn($reflectionClass);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->setMethods(array('createClassParser', 'parse'))
            ->getMock();

        $classParser
            ->expects($this->once())
            ->method('createClassParser')
            ->with($reflectionClass)
            ->willReturn($classParser);

        $classParser
            ->expects($this->once())
            ->method('parse')
            ->with($classMeta)
        ;
        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);

        //act
        $this->callObjectMethod($classParser, 'parseParent', $classMeta);
        //assert
    }
}