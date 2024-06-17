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
            ->onlyMethods(array(
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
            ->onlyMethods(array('getMethods'))
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
            ->onlyMethods(array('createMethodParser'))
            ->getMock();
        $classParser
            ->expects($this->any())
            ->method('createMethodParser')
            ->willReturnCallback(function(\ReflectionMethod $reflectionMethod) use($classMeta, $reflectionMethods){
                static $index = 0;
                switch($index++){
                    case 0:
                        $this->assertSame($reflectionMethods[0], $reflectionMethod);

                        $methodParser = $this->getMockBuilder(MethodParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $methodParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $methodParser;
                    case 1:
                        $this->assertSame($reflectionMethods[1], $reflectionMethod);

                        $methodParser = $this->getMockBuilder(MethodParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $methodParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $methodParser;
                    case 2:
                        $this->assertSame($reflectionMethods[2], $reflectionMethod);

                        $methodParser = $this->getMockBuilder(MethodParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $methodParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $methodParser;
                    default:
                        $this->fail('should not be called');
                }
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
            ->onlyMethods(array('getProperties'))
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
            ->onlyMethods(array('createPropertyParser'))
            ->getMock();

        $classParser
            ->expects($this->any())
            ->method('createPropertyParser')
            ->willReturnCallback(function(\ReflectionProperty $reflectionProperty) use($classMeta, $reflectionProperties){
                static $index = 0;
                switch ($index++){
                    case 0:
                        $this->assertSame($reflectionProperties[0], $reflectionProperty);

                        $propertyParser = $this->getMockBuilder(PropertyParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $propertyParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $propertyParser;
                    case 1:
                        $this->assertSame($reflectionProperties[1], $reflectionProperty);

                        $propertyParser = $this->getMockBuilder(PropertyParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $propertyParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $propertyParser;
                    case 2:
                        $this->assertSame($reflectionProperties[2], $reflectionProperty);

                        $propertyParser = $this->getMockBuilder(PropertyParser::class)
                            ->disableOriginalConstructor()
                            ->onlyMethods(array('parse'))
                            ->getMock();

                        $propertyParser
                            ->expects($this->once())
                            ->method('parse')
                            ->with($classMeta);

                        return $propertyParser;
                    default:
                        $this->fail('should not be called');
                }
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
            $this->getMockBuilder(ClassProcessorInterface::class)->onlyMethods(array('handleClass'))->getMockForAbstractClass(),
            new \stdClass(),
            $this->getMockBuilder(ClassProcessorInterface::class)->onlyMethods(array('handleClass'))->getMockForAbstractClass(),
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
            ->onlyMethods(array('getClassAnnotations'))
            ->getMock();

        $annotationReader
            ->expects($this->once())
            ->method('getClassAnnotations')
            ->with($reflectionClass)
            ->willReturn($annotations);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array('parseParent', 'parseTraits'))
            ->getMock();

        $classParser
            ->expects($this->once())
            ->method('parseParent')
            ->with($classMeta);

        $classParser
            ->expects($this->once())
            ->method('parseTraits')
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
            ->onlyMethods(array('getParentClass'))
            ->getMock();
        $reflectionClass
            ->expects($this->once())
            ->method('getParentClass')
            ->willReturn(false);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array('createClassParser', 'parse'))
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
            ->onlyMethods(array('getParentClass'))
            ->getMock();
        $reflectionClass
            ->expects($this->once())
            ->method('getParentClass')
            ->willReturn($reflectionClass);

        $classParser = $this->getMockBuilder(ClassParser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array('createClassParser', 'parse'))
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