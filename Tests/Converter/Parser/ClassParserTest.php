<?php
namespace RS\DiExtraBundle\Tests\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
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
        $callTimes = 0;
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
            ->getMock();

        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);
        $this->setObjectAttribute($classParser, 'annotationReader', $annotationReader);

        $mockMethodParser = \Mockery::mock('overload:'.MethodParser::class);
        $mockMethodParser
            ->shouldReceive('parse')
            ->andReturnUsing(function(ClassMeta $classMetaForTest) use($classMeta, &$callTimes){
                $this->assertSame($classMeta, $classMetaForTest);
                $callTimes++;
            });

        //act
        $this->callObjectMethod($classParser, 'parseMethod', $classMeta);

        //assert
        $this->assertEquals(count($reflectionMethods), $callTimes);
    }

    public function test_parseProperty()
    {
        //arrange
        $callTimes = 0;
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
            ->getMock();

        $this->setObjectAttribute($classParser, 'reflectionClass', $reflectionClass);
        $this->setObjectAttribute($classParser, 'annotationReader', $annotationReader);

        $mockPropertyParser = \Mockery::mock('overload:'.PropertyParser::class);
        $mockPropertyParser
            ->shouldReceive('parse')
            ->andReturnUsing(function(ClassMeta $classMetaForTest) use($classMeta, &$callTimes){
                $this->assertSame($classMeta, $classMetaForTest);
                $callTimes++;
            });

        //act
        $this->callObjectMethod($classParser, 'parseProperty', $classMeta);

        //assert
        $this->assertEquals(count($reflectionProperties), $callTimes);
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
            $this->getMockBuilder(ClassProcessorInterface::class)->getMockForAbstractClass(),
            new \stdClass(),
            $this->getMockBuilder(ClassProcessorInterface::class)->getMockForAbstractClass(),
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
}